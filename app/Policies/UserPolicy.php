<?php namespace Sneefr\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Sneefr\Models\User;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Check this user can create an ad.
     *
     * @param \Sneefr\Models\User $user
     *
     * @return bool
     */
    public function createAd(User $user)
    {
        return (bool) $user->administrableShops->count();
    }
}
