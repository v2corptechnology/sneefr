<?php namespace Sneefr\Support;

use Illuminate\Database\Eloquent\Collection;

class LikeCollection extends Collection
{
    /**
     * Check this ad is liked by the current user or the specified userId.
     *
     * @param int $byUserId (optional)
     *
     * @return bool
     */
    public function hasLiked(int $byUserId = null) : bool
    {
        // Retrieve either the passed user identifier or the one of current auth
        $byUserId = $byUserId ? $byUserId : (auth()->check() ? auth()->id() : 0);

        return (bool) $this->where('personId', $byUserId)->count();
    }
}
