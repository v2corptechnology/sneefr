<?php

namespace Sneefr\Listeners\AdWasPurchased;

use Illuminate\Contracts\Queue\ShouldQueue;
use Sneefr\Events\AdWasPurchased;
use Sneefr\Models\Evaluation;

class CreatePendingEvaluation implements ShouldQueue
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
        Evaluation::create([
            'shop_id'     => $event->ad->shop->id,
            'user_id'     => $event->buyer->id,
            'ad_id'       => $event->ad->id,
            'status'      => Evaluation::STATUS_PENDING,
            'is_positive' => 1,
            'body'        => null,
        ]);
    }
}
