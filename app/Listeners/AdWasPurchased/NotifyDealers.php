<?php

namespace Sneefr\Listeners\AdWasPurchased;

use Illuminate\Contracts\Queue\ShouldQueue;
use Sneefr\Events\AdWasPurchased;
use Sneefr\Models\Notification;

class NotifyDealers implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  AdWasPurchased  $event
     * @return void
     */
    public function handle(AdWasPurchased $event)
    {
        Notification::create([
            'user_id'         => $event->buyer->getId(),
            'notifiable_type' => get_class($event->ad),
            'notifiable_id'   => $event->ad->getId(),
            'is_special'      => true,
        ]);

        Notification::create([
            'user_id'         => $event->ad->seller->getId(),
            'notifiable_type' => get_class($event->ad),
            'notifiable_id'   => $event->ad->getId(),
            'is_special'      => true,
        ]);
    }
}
