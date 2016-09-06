<?php namespace Sneefr;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Sneefr\Jobs\Notify;
use Sneefr\Models\Follow;
use Sneefr\Models\User;

/**
 * Class FollowsUser
 *
 * @package \Sneefr
 */
class FollowsUser
{
    use DispatchesJobs;

    /**
     * @var \Sneefr\Models\User
     */
    protected $user;

    /**
     * Add a follow to a place.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function follow(Request $request)
    {
        $this->user = $this->findUser($request->get('item'));

        // Store the follow
        auth()->user()->followedUsers()->attach($this->user->getId());

        $follow = Follow::where('user_id', auth()->id())
            ->where('followable_type', 'user')
            ->where('followable_id', $this->user->getId())
            ->first();

        // Dispatch notifications
        $this->dispatch(new Notify($follow));
    }

    /**
     * Remove a follow to a place.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function unfollow(Request $request)
    {
        $this->user = $this->findUser($request->get('item'));

        if (is_null($this->user)) {
            abort(404, 'This user does not exists');
        }

        // Remove the follow
        auth()->user()->followedUsers()->detach($this->user->getId());
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectStore()
    {
        return redirect()->route('profiles.show', $this->user)
            ->with('success', trans('feedback.follow_activity_success'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectDestroy()
    {
        return redirect()->route('profiles.show', $this->user)
            ->with('success', trans('feedback.user_unfollow_success'));
    }

    /**
     * Fetch the user.
     *
     * @param string $hash
     *
     * @return \Sneefr\Models\User
     * @throws \InvalidArgumentException
     */
    protected function findUser(string $hash)
    {
        $followedId = app('hashids')->decode($hash);

        // Check if it matches something
        if (! isset($followedId[0])) {
            throw new \InvalidArgumentException("Passed hash {$followedId} could not be decoded");
        }

        return User::findOrFail($followedId[0]);
    }
}
