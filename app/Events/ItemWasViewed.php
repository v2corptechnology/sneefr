<?php

namespace Sneefr\Events;

use Illuminate\Queue\SerializesModels;
use Sneefr\Models\Ad;
use Sneefr\Models\User;

class ItemWasViewed
{
    use SerializesModels;

    /**
     * @var \Sneefr\Models\Ad
     */
    public $ad;

    /**
     * @var \Sneefr\Models\User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param \Sneefr\Models\Ad   $ad
     * @param \Sneefr\Models\User $user
     */
    public function __construct(Ad $ad, User $user = null)
    {
        $this->ad = $ad;
        $this->user = $user;
    }
}
