<?php namespace Sneefr\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Sneefr\Models\Follow;

trait Follower
{
    /**
     * Collection of objects being followed by this follower.
     *
     * @return \Illuminate\Support\Collection
     */
    public function following() : Collection
    {
        return $this->hasMany(Follow::class)
            ->with('followable')
            ->get()
            ->lists('followable');
    }

    /**
     * Accessor to fake the "following" relation method.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getFollowingAttribute() : Collection
    {
        return $this->following();
    }

    /**
     * Follows a specific followable.
     *
     * @param \Illuminate\Database\Eloquent\Model $followable
     *
     * @return $this
     */
    public function follow(Model $followable)
    {
        $followable->followers()->save($this);

        return $this;
    }

    /**
     * Unfollows a specific followable.
     *
     * @param \Illuminate\Database\Eloquent\Model $followable
     *
     * @return $this
     */
    public function unfollow(Model $followable)
    {
        $followable->followers()->detach($this);

        return $this;
    }
}
