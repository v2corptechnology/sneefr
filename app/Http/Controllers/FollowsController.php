<?php namespace Sneefr\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Sneefr\FollowsPlace;
use Sneefr\FollowsShop;
use Sneefr\FollowsUser;
use Sneefr\Jobs\UpdateRank;

class FollowsController extends Controller
{
    use DispatchesJobs;

    /**
     * Create a follow.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $strategy = $this->getFollowStrategy($request);

        // Add a follow with the correct strategy
        $strategy->follow($request);

        // Update the gamification info
        $this->dispatch(new UpdateRank(auth()->id()));

        // Redirect to the correct page
        return $strategy->redirectStore();
    }

    /**
     * Remove a follow.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $strategy = $this->getFollowStrategy($request);

        // Add a follow with the correct strategy
        $strategy->unfollow($request);

        // Update the gamification info
        $this->dispatch(new UpdateRank(auth()->id()));

        // Redirect to the correct page
        return $strategy->redirectDestroy();
    }

    /**
     * Determine the strategy to use for the follow.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Sneefr\FollowsPlace|\Sneefr\Http\Controllers\FollowsShop
     */
    protected function getFollowStrategy(Request $request)
    {
        if ($request->get('type') == 'shop') {
            return new FollowsShop;
        }

        if ($request->get('type') == 'place') {
            return new FollowsPlace;
        }

        if ($request->get('type') == 'user') {
            return new FollowsUser;
        }

        // Todo: handle it the nice way in a form request ?
        abort(403, 'Following shop or places only');
    }
}
