<?php namespace Sneefr\Events;

use Sneefr\Models\Ad;

class AdWasUpdated extends Event
{
    /**
     * @var \Sneefr\Models\Ad
     */
    public $ad;

    /**
     * Create a new event instance.
     *
     * @param \Sneefr\Models\Ad $ad
     */
    public function __construct(Ad $ad)
    {
        $this->ad = $ad;
    }
}
