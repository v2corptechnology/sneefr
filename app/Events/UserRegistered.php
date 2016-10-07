<?php

namespace Sneefr\Events;

use Illuminate\Queue\SerializesModels;
use Sneefr\Models\User;

class UserRegistered extends Event
{
    use SerializesModels;

    /**
     * @var \Sneefr\Events\User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param \Sneefr\Models\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
