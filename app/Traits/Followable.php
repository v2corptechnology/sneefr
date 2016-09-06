<?php namespace Sneefr\Traits;

use Sneefr\Models\User;

trait Followable
{
    /**
     * Collection of followers attached to this object.
     *
     * @return mixed
     */
    public function followers()
    {
        return $this->morphToMany(
            User::class,        // related
            'followable',       // name
            'follows',          // table
            'followable_id',    // foreignKey
            'user_id'           // otherKey
        )->withPivot('created_at', 'updated_at');
    }

    /**
     * Check this followable is being followed by a user.
     *
     * @param int $userId
     *
     * @return bool
     */
    public function isFollowedBy(int $userId) : bool
    {
        return $this->followers()
            ->where('user.id', $userId)
            ->count() > 0;
    }
}
