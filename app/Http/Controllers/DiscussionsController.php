<?php namespace Sneefr\Http\Controllers;

use Gate;
use Illuminate\Http\Request;
use Sneefr\Events\AdWasUpdated;
use Sneefr\Jobs\Notify;
use Sneefr\Jobs\SendDealRecapToBuyer;
use Sneefr\Jobs\SendDealRecapToSeller;
use Sneefr\Models\Ad;
use Sneefr\Models\Discussion;
use Sneefr\Models\Shop;
use Sneefr\Repositories\Ad\AdRepository;
use Sneefr\Repositories\Discussion\DiscussionRepository;

class DiscussionsController extends Controller
{
    /**
     * @var \Sneefr\Repositories\Discussion\DiscussionRepository
     */
    private $discussionRepository;

    /**
     * @var \Sneefr\Repositories\Ad\AdRepository
     */
    private $adRepository;

    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    public function __construct(
        DiscussionRepository $discussionRepository,
        AdRepository $adRepository,
        Request $request
    )
    {
        $this->discussionRepository = $discussionRepository;
        $this->adRepository = $adRepository;
        $this->request = $request;
    }

    /**
     * List all the discussions of a user and display the latest one.
     *
     * @param string $shopSlug (optional)
     *
     * @return \Illuminate\View\View
     */
    public function index(Request$request, string $shopSlug = null)
    {
        // Todo: move it to a policy
        $this->guardAgainstWrongShop($shopSlug);

        $discussions = $this->getDiscussions($shopSlug);

        $type = $request->is('shopDiscussions/*') ? 'shop' : 'user';

        return view('discussions.index', compact('discussions', 'shopSlug', 'type'));
    }

    /**
     * Display a specific discussion.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $discussionId
     * @param string                   $shopSlug (optional)
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request, int $discussionId, string $shopSlug = null)
    {
        // Todo: move it to a policy
        $this->guardAgainstWrongShop($shopSlug);

        $discussions = $this->getDiscussions($shopSlug);

        $chosenDiscussion = $discussions->where('id', $discussionId)->first();

        if (! $shopSlug) {
            $this->authorize('show-discussion', $chosenDiscussion);
        }

        $type = $request->is('shopDiscussions/*') ? 'shop' : 'user';

        // Mark the messages from this discussion as "read"
        //$this->markRead($chosenDiscussion);

        return view('discussions.index', compact('discussions', 'chosenDiscussion', 'shopSlug', 'type'));
    }

    /**
     * Remove an ad from this discussion.
     *
     * @param int $discussionId
     * @param int $adId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeAd(int $discussionId, int $adId)
    {
        $discussion = $this->discussionRepository->get($discussionId);

        $this->authorize('show-discussion', $discussion);

        $ad = Ad::where('id', $adId)->withTrashed()->first();

        // The event must be fired before the actual removal
        // because discussed ads are soft deleted
        event(new AdWasUpdated($ad));

        $discussion->removeAd($adId);

        $discussionsRoute = auth()->user()->shops ? 'discussions.index' : 'discussions.index';

        return redirect()->route($discussionsRoute, ['#latest']);
    }

    /**
     * Show the screen to sell an ad to someone.
     *
     * @param int $discussionId
     * @param int $adId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sell(int $discussionId, int $adId)
    {
        $ad = $this->adRepository->find($adId);

        $discussion = $this->discussionRepository->get($discussionId);

        $this->authorize('show-discussion', $discussion);

        $this->authorize('update', $ad);

        $buyer = $discussion->recipient();

        return view('discussions.sell', compact('buyer', 'ad', 'discussion'));
    }

    /**
     * Mark the ad as sold to this buyer.
     *
     * @param int                      $buyerId
     * @param int                      $adId
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sold(int $buyerId, int $adId, Request $request)
    {
        // Get the ad
        $ad = $this->adRepository->find($adId);

        // Create or retrieve the discussion
        $discussion = $this->discussionRepository->start(auth()->id(), $buyerId);

        $this->authorize('show-discussion', $discussion);
        $this->authorize('update', $ad);

        // Lock the ad for this user
        $ad = $ad->lockFor($buyerId);

        // Discuss this ad if not yet in discussion
        $discussion->discussAd($ad->getId());

        // Fill info relative to the transaction
        if(is_numeric($request->get('final_amount'))){
            $finalAmount = (int) $request->get('final_amount') * 100 ;
        }else{
            $finalAmount = $ad->price();
        }
        
        $isSecurePayment = $request->get('secure') === "true";
        $ad->fill(['final_amount' => $finalAmount, 'is_secure_payment' => $isSecurePayment]);
        $ad->save();

        event(new AdWasUpdated($ad));

        // Send the recap emails
        $this->dispatch(new SendDealRecapToSeller($ad));
        $this->dispatch(new SendDealRecapToBuyer($ad));
        
        if($discussion->shop_id){
            return redirect()->route('shop_discussions.show', [$discussion->id(), $discussion->shop, '#latest']);    
        }
        return redirect()->route('discussions.show', [$discussion->id(), '#latest']);
    }

    /**
     * Mark user's unread messages as read.
     *
     * @param int|\Sneefr\Models\Discussion $discussion
     */
    public function markRead($discussion)
    {
        if (!$discussion instanceof Discussion) {
            $discussion = $this->discussionRepository->get($discussion);
        }

        $this->authorize('show-discussion', $discussion);

        $discussion->markMessagesAsRead();
    }

    /**
     * Choose an ad to sell in this discussion scope.
     *
     * @param int                                  $discussionId
     * @param \Sneefr\Repositories\Ad\AdRepository $adRepository
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function chooseAd(int $discussionId, AdRepository $adRepository)
    {
        $discussion = $this->discussionRepository->get($discussionId);

        $this->authorize('show-discussion', $discussion);

        $inDiscussion = $discussion->myUnlockedAds();

        $outOfDiscussion = Ad::where('user_id', auth()->user()->id)
            ->whereNotIn('id', $inDiscussion->pluck('id'))
            ->whereNull('locked_for')
            ->get();

        // When only one ad is available, redirect without asking to choose the ad
        $totalAds = $inDiscussion->merge($outOfDiscussion);
        
        if ($totalAds->count() == 1) {
            return redirect()->route('discussions.ads.show', [$discussion->id(), $totalAds->first()->slug()]);
        }

        return view('discussions.chooseAd',
            compact('discussion', 'inDiscussion', 'outOfDiscussion'));
    }

    private function guardAgainstWrongShop($shopSlug = null)
    {
        if ($shopSlug && $shopSlug != auth()->user()->shops->first()->getRouteKey()) {
            abort(403, 'You are not authorized to view this discussion');
        }
    }

    private function getDiscussions($shopSlug = null)
    {
        if ($shopSlug) {
            $shop = Shop::where('slug', $shopSlug)->first();

            return $discussions = $this->discussionRepository->ofShop($shop->getId());
        }

        $user = auth()->user();
        $shopId = $user->shops->isEmpty() ? null : $user->shops->first()->getId();

        return $discussions = $this->discussionRepository->of(auth()->id())->exceptFromShop($shopId);
    }
}
