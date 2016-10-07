<?php

namespace Sneefr\Listeners\AdWasPurchased;

use Sneefr\Events\AdWasPurchased;

class UpdateStock
{
    /**
     * Handle the event.
     *
     * @param  AdWasPurchased $event
     *
     * @return void
     */
    public function handle(AdWasPurchased $event)
    {
        $event->ad->decrement('remaining_quantity');

        // If no remaining stock, remove the ad
        if ($event->ad->remaining_quantity <= 0) {
            $event->ad->delete();
            $event->ad->removeFromIndex();
        }
    }
}
