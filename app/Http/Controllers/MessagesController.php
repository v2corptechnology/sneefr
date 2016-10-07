<?php

namespace Sneefr\Http\Controllers;

use Sneefr\Http\Requests\Request;
use Sneefr\Http\Requests\StoreMessageRequest;
use Sneefr\Jobs\PushMessage;
use Sneefr\Models\Discussion;
use Sneefr\Models\Shop;
use Sneefr\Repositories\Discussion\DiscussionRepository;
use Sneefr\Repositories\Shop\ShopRepository;

class MessagesController extends Controller
{
    /**
     * @var \Sneefr\Repositories\Discussion\DiscussionRepository
     */
    protected $discussionRepository;

    /**
     * @var \Sneefr\Repositories\Shop\ShopRepository
     */
    protected $shopRepository;

    /**
     * MessagesController constructor.
     *
     * @param \Sneefr\Repositories\Discussion\DiscussionRepository $discussionRepository
     * @param \Sneefr\Repositories\Shop\ShopRepository             $shopRepository
     */
    public function __construct(DiscussionRepository $discussionRepository, ShopRepository $shopRepository)
    {
        $this->discussionRepository = $discussionRepository;
        $this->shopRepository = $shopRepository;
    }

    /**
     * Add a message to a discussion.
     *
     * @param \Sneefr\Http\Requests\StoreMessageRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(StoreMessageRequest $request)
    {
        // Retrieve or create the discussion
        $discussion = $this->retrieveDiscussion($request);

        // Attach message
        $message = $discussion->post([
            'to_user_id' => $this->getRecipientId($discussion),
            'body' => $request->get('body'),
        ]);

        // Send notifications
        $this->dispatch(new PushMessage($discussion, $message));

        // Attach the discussed ad if necessary
        if ($request->has('ad_id')) {
            $discussion->discussAd($request->get('ad_id'));
        }

        // When sent through ajax messaging, empty answer
        if ($request->ajax()) {
            return response()->json([]);
        }

        $redirect = $discussion->shop && $discussion->shop->isOwner()
            ? route('shop_discussions.index', [$discussion->shop, '#latest'])
            : route('discussions.index', ['#latest']);

        return redirect($redirect);
    }

    /**
     * @param \Sneefr\Http\Requests\Request $request
     *
     * @return \Sneefr\Models\Discussion
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function retrieveDiscussion(Request $request) : Discussion
    {
        // If a discussion context is passed, check the user access to it
        if ($request->has('discussion_id')) {

            $discussion = $this->discussionRepository->get($request->get('discussion_id'));

            $this->authorize('show-discussion', $discussion);

        }
        // If the discussions aims a shop
        elseif ($request->get('recipient_is_shop')) {

            // Retrieve shop and owner
            $shop = Shop::where('slug', $request->get('recipient_identifier'))
                ->with('owner')
                ->first();

            // Create discussion with shop
            $discussion = $this->discussionRepository->startWithShop(auth()->id(), $shop);

        }
        // Otherwise, we assume it is a normal discussion between users
        else {

            $userId = app('Hashids\Hashids')->decode($request->get('recipient_identifier'))[0];

            $discussion = $this->discussionRepository->start(auth()->id(), $userId);

        }

        return $discussion;
    }

    /**
     * Get the recipient's id.
     *
     * @param \Sneefr\Models\Discussion $discussion
     *
     * @return int
     */
    protected function getRecipientId(Discussion $discussion) : int
    {
        return $discussion->participants->reject(function ($participant) {
            return $participant->getId() == auth()->id();
        })
            ->first()
            ->getId();
    }
}
