<?php

namespace Sneefr\Listeners\UserRegistered;

use Sneefr\Events\UserRegistered;
use Sneefr\Models\ActionLog;

class LogLogin
{
    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        ActionLog::create([
            'type'    => ActionLog::USER_LOGIN,
            'user_id' => $event->user->getId(),
        ]);
    }
}
