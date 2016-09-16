<?php namespace Sneefr\Listeners\AdWasPurchased;

use Sneefr\Events\AdWasPurchased;
use Sneefr\Models\Referral;

class StoreSuccessfulDealConnections
{
    /**
     * Handle the event.
     *
     * @param \Sneefr\Events\AdWasPurchased $event
     */
    public function handle(AdWasPurchased $event)
    {
        Referral::updateOrCreate([
            'referent_user_id' => $event->buyer->getId(),
            'referred_user_id' => $event->ad->seller->getId(),
        ]);
        Referral::updateOrCreate([
            'referent_user_id' => $event->ad->seller->getId(),
            'referred_user_id' => $event->buyer->getId(),
        ]);
    }
}
