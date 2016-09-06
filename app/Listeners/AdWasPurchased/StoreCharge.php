<?php namespace Sneefr\Listeners\AdWasPurchased;

use Illuminate\Contracts\Queue\ShouldQueue;
use Sneefr\Events\AdWasPurchased;
use Sneefr\Models\Transaction;

class StoreCharge implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param \Sneefr\Events\AdWasPurchased $event
     */
    public function handle(AdWasPurchased $event)
    {
        $transaction = new Transaction();
        $transaction->user_id = $event->buyer->getId();
        $transaction->ad_id = $event->ad->getId();
        $transaction->data = $event->charge;
        $transaction->save();
    }
}
