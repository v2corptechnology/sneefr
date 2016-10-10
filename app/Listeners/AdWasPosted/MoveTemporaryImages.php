<?php

namespace Sneefr\Listeners\AdWasPosted;

use Illuminate\Contracts\Filesystem\Factory;
use Sneefr\Events\AdWasPosted;

class MoveTemporaryImages
{
    /**
     * @var \Illuminate\Contracts\Filesystem\Factory
     */
    private $disk;

    /**
     * Create the event listener.
     *
     * @param \Illuminate\Contracts\Filesystem\Factory $filesystemFactory
     */
    public function __construct(Factory $filesystemFactory)
    {
        $this->disk = $filesystemFactory->disk('images');
    }

    /**
     * Handle the event.
     *
     * @param  AdWasPosted $event
     *
     * @return void
     */
    public function handle(AdWasPosted $event)
    {
        foreach ($event->ad->imageNames() as $name) {

            $tempPath = "temp/" . $event->ad->user_id . "/" . $name;

            $path = "originals/" . $event->ad->getId() . "/" . $name;

            $this->disk->move($tempPath, $path);
        }
    }
}
