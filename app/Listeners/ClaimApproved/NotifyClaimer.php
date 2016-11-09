<?php

namespace Sneefr\Listeners\ClaimApproved;

use Sneefr\Events\ClaimApproved;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyClaimer implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ClaimApproved  $event
     * @return void
     */
    public function handle(ClaimApproved $event)
    {
        dd('send claim approved email');
    }
}
