<?php

namespace Sneefr\Listeners\AdWasPurchased;

use Illuminate\Contracts\Queue\ShouldQueue;
use Sneefr\Events\AdWasPurchased;
use Sneefr\Events\AdWasUpdated;
use Sneefr\Models\Transaction;

class SaveTransaction implements ShouldQueue
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
        $request = collect($event->request);

        $details = [
            'delivery' => [
                'method'       => $request->get('delivery', 'c2c'),
                'shop_address' => $request->get('pick-address'),
                'fee'          => $request->has('delivery')
                    ? $event->ad->delivery->amountFor($request->get('delivery'))
                    : null,
                'extra_info'   => $request->get('extra'),
            ],
        ];

        $transaction = new Transaction();
        $transaction->ad_id = $event->ad->getId();
        $transaction->buyer_id = $event->buyer->getId();
        $transaction->seller_id = $event->ad->seller->getId();
        $transaction->stripe_data = (array) $event->charge;
        $transaction->details = $details;
        $transaction->save();

        // Remove the ad from discussion, the pusher way
        event(new AdWasUpdated($event->ad));
    }
}
