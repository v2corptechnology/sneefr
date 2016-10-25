<?php

namespace Sneefr\Listeners\AdWasPosted;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Session\Store as Session;
use Sneefr\Events\AdWasPosted;
use Sneefr\Models\User;

class CopyAdLocationToProfile
{
    use DispatchesJobs;

    /**
     * @param \Illuminate\Session\Store $session
     */
    protected $session;

    /**
     * Create new instance of event
     *
     * @param \Illuminate\Session\Store $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Copy ad's location to the user if none filled.
     *
     * @param \Sneefr\Events\AdWasPosted $event
     */
    public function handle(AdWasPosted $event)
    {
        if ($event->ad->seller->getLatitude() && $event->ad->seller->getLongitude()) {
            return;
        }

        User::find($event->ad->seller->getId())->update([
            'location' => $event->ad->location(),
            'lat'      => $event->ad->latitude(),
            'long'     => $event->ad->longitude(),
        ]);

        $this->session->flash('success', trans(
            'feedback.copied_ad_location_to_your_profile',
            ['url' => route('me.show', $event->ad->seller) . '#settings']
        ));
    }
}
