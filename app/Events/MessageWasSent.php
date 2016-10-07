<?php

namespace Sneefr\Events;

use Sneefr\Models\Message;
use Sneefr\Models\User;

class MessageWasSent extends Event
{
    /**
     * @var \Sneefr\Models\User
     */
    public $sender;

    /**
     * @var \Sneefr\Models\User
     */
    public $receiver;

    /**
     * @var \Sneefr\Models\Message
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @param \Sneefr\Models\User    $sender
     * @param \Sneefr\Models\User    $receiver
     * @param \Sneefr\Models\Message $message
     */
    public function __construct(User $sender, User $receiver, Message $message)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->message = $message;
    }

}
