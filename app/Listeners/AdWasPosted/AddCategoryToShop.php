<?php

namespace Sneefr\Listeners\AdWasPosted;

use Sneefr\Events\AdWasPosted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddCategoryToShop implements ShouldQueue
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
     * @param  AdWasPosted  $event
     * @return void
     */
    public function handle(AdWasPosted $event)
    {
        // Add category of new Ad to shop
        $event->ad->shop->categories()->syncWithoutDetaching([$event->ad->category_id]);
    }
}
