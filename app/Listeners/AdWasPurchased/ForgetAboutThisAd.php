<?php namespace Sneefr\Listeners\AdWasPurchased;

use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sneefr\Events\AdWasPurchased;
use Sneefr\Events\AdWasUpdated;

class ForgetAboutThisAd implements ShouldQueue
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

        $deliveryCost = $request->has('delivery') ? $event->ad->delivery->amountFor($request->get('delivery')) : null;

        $transaction = [
            'delivery' => [
                'method'           => $request->get('delivery', 'c2c'),
                'shop_address'     => $request->get('pick-address'),
                'fee'              => $deliveryCost,
                'extra_info'       => $request->get('extra'),
            ],
        ];

        // Save info on the ad
        $event->ad->sold_to = $event->buyer->getId();
        $event->ad->transaction = $transaction;
        $event->ad->deleted_at = Carbon::now();
        $event->ad->save();

        // Remove the ad from Algolia
        $event->ad->removeFromIndex();

        // Remove the ad from discussion, the pusher way
        event(new AdWasUpdated($event->ad));
    }
}
