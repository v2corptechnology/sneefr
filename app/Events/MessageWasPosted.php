<?php

namespace Sneefr\Events;

use Illuminate\Queue\SerializesModels;
use Sneefr\Models\Message;
use Sneefr\Models\User;

class MessageWasPosted
{
    use SerializesModels;

    /**
     * @var \Sneefr\Models\Message
     */
    public $message;

    /**
     * @var \Sneefr\Models\User
     */
    public $sender;

    /**
     * Create a new event instance.
     *
     * @param \Sneefr\Models\Message $message
     * @param \Sneefr\Models\User    $sender
     */
    public function __construct(Message $message, User $sender)
    {
        $this->message = $message;
        $this->sender = $sender;
    }
}
