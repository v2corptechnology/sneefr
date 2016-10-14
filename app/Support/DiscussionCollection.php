<?php

namespace Sneefr\Support;

use Illuminate\Database\Eloquent\Collection;

class DiscussionCollection extends Collection
{
    /**
     * Filter the discussions that have unread messages for a user.
     *
     * @param int $userId
     *
     * @return static
     */
    public function unread(int $userId = null)
    {
        // Get the asked user id or grab it from auth.
        $userId = $userId ?? auth()->id();

        return $this->filter(function ($discussion) use ($userId) {
            return $discussion->messages->unread($userId)->count();
        });
    }

    /**
     * @param mixed $id
     *
     * @return static
     */
    public function exceptFromShop($id)
    {
        $exceptIds = (array) $id;

        return $this->reject(function ($discussion) use ($exceptIds) {
            return in_array($discussion->shop_id, $exceptIds);
        });
    }
}
