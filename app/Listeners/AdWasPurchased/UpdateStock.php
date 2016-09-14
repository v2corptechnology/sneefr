<?php namespace Sneefr\Listeners\AdWasPurchased;

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
        $event->ad->stock->decrement();

        // If no remaining stock, throw an event
        if ($event->ad->stock <= 0) {
            event( new AdRanOutOfStock($event->ad));
        }
    }
}
