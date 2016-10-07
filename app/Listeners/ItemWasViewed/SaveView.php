<?php

namespace Sneefr\Listeners\ItemWasViewed;

use Illuminate\Contracts\Queue\ShouldQueue;
use Sneefr\Events\ItemWasViewed;
use Sneefr\Models\ActionLog;

class SaveView implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  ItemWasViewed $event
     *
     * @return void
     */
    public function handle(ItemWasViewed $event)
    {
        // Get the user id if there was one
        $user_id = $event->user ? $event->user->getId() : null;

        return ActionLog::create([
            'type'    => ActionLog::AD_VIEW,
            'user_id' => $user_id,
            'context' => json_encode(['id' => $event->ad->getId()]),
        ]);
    }
}
