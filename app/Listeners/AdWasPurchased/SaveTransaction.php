<?php

namespace Sneefr\Listeners\AdWasPurchased;

use Illuminate\Contracts\Queue\ShouldQueue;
use Sneefr\Events\AdWasPurchased;
use Sneefr\Events\AdWasUpdated;
use Sneefr\Models\Transaction;
use Sneefr\Price;

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
        $price = new Price($event->charge->amount);
        $request = collect($event->request);

        $details = [
            'delivery' => [
                'method'       => $request->get('delivery', 'c2c'),
                'shop_address' => $request->get('pick-address'),
                'fee'          => $request->has('delivery')
                    ? $event->ad->delivery->amountFor($request->get('delivery'))
                    : null,
            ],
            'details'  => [
                'quantity'   => $request->get('quantity', 1),
                'extra_info' => $request->get('extra'),
            ],
            'charge'   => [
                'amount'   => $event->charge->amount,
                'currency' => $event->charge->currency,
                'price'    => $price->readable2(),
                'data'     => (array) $event->charge,
            ],
        ];

        $transaction = new Transaction();
        $transaction->ad_id = $event->ad->getId();
        $transaction->buyer_id = $event->buyer->getId();
        $transaction->seller_id = $event->ad->seller->getId();
        $transaction->details = $details;
        $transaction->save();

        // Remove the ad from discussion, the pusher way
        event(new AdWasUpdated($event->ad));
    }
}
