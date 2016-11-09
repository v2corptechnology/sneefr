<?php

namespace Sneefr\Listeners\ClaimRejected;

use Sneefr\Events\ClaimRejected;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyClaimer implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ClaimRejected  $event
     * @return void
     */
    public function handle(ClaimRejected $event)
    {
        dd('send reject message to claimer');
    }
}
