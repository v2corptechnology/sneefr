<?php namespace Sneefr\Events;

use Illuminate\Queue\SerializesModels;

class AdWasDeleted extends Event
{
    use SerializesModels;

    public $adId;

    /**
     * Create a new event instance.
     *
     * @param $adId
     */
    public function __construct($adId)
    {
        $this->adId = $adId;
    }

}
