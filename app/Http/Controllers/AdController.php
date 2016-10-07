<?php

namespace Sneefr\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Sneefr\Events\ItemWasViewed;
use Sneefr\Http\Requests\CreateAdRequest;
use Sneefr\Jobs\DeleteAd;
use Sneefr\Models\Ad;
use Sneefr\Models\Referral;
use Sneefr\Models\Shares;
use Sneefr\Models\User;
use Sneefr\Repositories\Category\CategoryRepository;
use Sneefr\Repositories\Discussion\DiscussionRepository;

class AdController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show($id)
    {
        // Get the ad
        $ad = Ad::withTrashed()->with('seller.evaluations.user')->findOrFail($id);

        // Verify this ad is viewable
        // Quickfix : a disconnected user cannot see an ad
        //$this->authorize($ad);

        // Todo: extract it to the User model/whatever
        $relationships = $this->getRelationShips($ad->seller);

        event(new ItemWasViewed($ad, auth()->user()));

        return view('ad.show', compact('ad', 'relationships'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int                                  $id
     * @param \Sneefr\Http\Requests\CreateAdRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id, CreateAdRequest $request)
    {
        // Get the ad to edit
        $ad = Ad::findOrFail($id);

        // Check the rights for this user to edit this ad
        $this->authorize('update', $ad);

        // Update the data
        $ad->update($request->except(['images']));

        return redirect()->route('ad.show', $ad)
            ->with('success', trans('feedback.ad_edit_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $adId
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($adId)
    {
        $ad = Ad::findOrFail($adId);

        // Check the rights for this user to remove this ad
        $this->authorize('destroy', $ad);

        $this->dispatch(new DeleteAd($adId));

        return redirect()->home()
            ->with('success', trans('feedback.ad_delete_success', ['url' => route('items.create')]));
    }

    /**
     * Allow the user to choose the buyer of this ad.
     *
     * @param int                                                  $adId
     * @param \Sneefr\Repositories\Discussion\DiscussionRepository $discussionRepository
     *
     * @return \Illuminate\View\View
     */
    public function chooseBuyer(int $adId, DiscussionRepository $discussionRepository)
    {
        // Todo: check if ad is not locked
        // Get the ad to edit
        $ad = Ad::findOrFail($adId);

        //$this->authorize('edit', $ad);
        if ($ad->user_id != auth()->id() && ! auth()->user()->isAdmin()) {
            return redirect('/');
        }

        // Persons with the ad in discussion
        $discussing = $discussionRepository->discussingAd($ad->getId());

        // Discussions without the ad
        $notDiscussing = $discussionRepository->of(auth()->id())
            ->reject(function ($discussion) use ($discussing) {
                return in_array($discussion->id, $discussing->pluck('id')->toArray());
            });

        return view('discussions.chooseBuyer', compact('discussing', 'notDiscussing', 'ad'));
    }

    /**
     * Generate the fragement needed by a discussion for a specific ad.
     *
     * @param int                                                  $adId
     * @param \Illuminate\Http\Request                             $request
     * @param \Sneefr\Repositories\Discussion\DiscussionRepository $discussionRepository
     *
     * @return mixed
     * @throws \Exception
     */
    public function getAdFragment(int $adId, Request $request, DiscussionRepository $discussionRepository)
    {
        // Get the specific discussion
        $discussion = $discussionRepository->get($request->input('discussion_id'));

        // Ads in this discussion
        $ads = $discussion->allAds->sortByDesc('pivot.updated_at');

        foreach ($ads as $ad) {
            if ($ad->getId() == $adId) {
                $data = [
                    'fragment' => view('discussions._ad')->with([
                        'ad'                => $ad,
                        'currentDiscussion' => $discussion,
                    ])->render(),
                ];

                return response()->json($data);
            }
        }

        abort(404);
    }

    /**
     * Generates an array with all different kind of relationships
     *
     * @param \Sneefr\Models\User $seller
     *
     * @return array
     */
    protected function getRelationShips(User $seller) : array
    {
        $common = collect();

        // Fetch commons only if the user is logged and is not the seller
        if (auth()->check() && auth()->id() != $seller->getId()) {
            $sellerReferences = Referral::where('referent_user_id', $seller->getId())->get()->pluck('referred_user_id');
            $userReferences = Referral::where('referent_user_id', auth()->user()->getId())->get()->pluck('referred_user_id');

            $commonReferenceIds = $sellerReferences->intersect($userReferences);

            $common = User::find($commonReferenceIds->toArray());
        }

        // All relationships the seller has
        $other = collect();

        return [
            'common' => $common,
            'other'  => $other,
            'all'    => collect(),
        ];
    }
}
