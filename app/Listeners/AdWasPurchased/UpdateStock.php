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
        // Easy manipulation
        $request = collect($event->request);

        $event->ad->decrement('remaining_quantity', $request->get('quantity', 1));

        // If no remaining stock, remove the ad
        if ($event->ad->remaining_quantity <= 0) {
            $event->ad->delete();
            $event->ad->removeFromIndex();
        }
    }
}
