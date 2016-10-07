<?php

namespace Sneefr\Support;

use Illuminate\Database\Eloquent\Collection;

class MessageCollection extends Collection
{
    /**
     * Filter the messages that are unread for a user.
     *
     * @param int $userId
     *
     * @return static
     */
    public function unread(int $userId = null)
    {
        // Get the asked user id or grab it from auth.
        $userId = $userId ?? auth()->id();

        return $this->filter(function ($message) use ($userId) {
            return is_null($message->read_at) && $message->to_user_id == $userId;
        });
    }
}
