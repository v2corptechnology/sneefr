<?php

namespace Sneefr\Listeners\UserRegistered;

use Illuminate\Contracts\Filesystem\Factory;
use Sneefr\Events\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddAvatar
{
    protected $disk;

    protected $imageService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Factory $filesystemFactory)
    {
        $this->disk = $filesystemFactory->disk('avatars');
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        if(!$event->user->getSocialNetworkId()){
            return false;
        }

        // Path for uploading
        $path = "avatar/" . $event->user->getId() . '.jpg';

        $url = 'http://graph.facebook.com/' . $event->user->getSocialNetworkId() . '/picture?type=large';

        $file = file_get_contents($url);
        
        // Move the file
        if (! $this->disk->put($path, $file)) {
            // add logs
            return false;
        }

        $event->user->avatar = $event->user->getId() . 'jpg';
        $event->user->save();
    }
}
