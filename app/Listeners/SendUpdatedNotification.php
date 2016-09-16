<?php

namespace Sneefr\Listeners;

use LaravelPusher;
use Sneefr\Events\AdWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sneefr\Repositories\Discussion\DiscussionRepository;

class SendUpdatedNotification
{
    /**
     * @var \Sneefr\Repositories\Discussion\DiscussionRepository
     */
    private $discussionRepository;

    /**
     * Create the event listener.
     *
     * @param \Sneefr\Repositories\Discussion\DiscussionRepository $discussionRepository
     */
    public function __construct(DiscussionRepository $discussionRepository)
    {
        $this->discussionRepository = $discussionRepository;
    }

    /**
     * Handle the event.
     *
     * @param  AdWasUpdated  $event
     * @return void
     */
    public function handle(AdWasUpdated $event)
    {
        // Shortcut for ad
        $ad = $event->ad;

        // Get discussions with this ad
        $discussions = $this->discussionRepository->discussingAd($ad->getId());

        // Extract all the participants from those discussions
        $participants = $discussions->pluck('participants')->collapse();

        // Generate pusher channels name to broadcast on
        $channelsToNotify = $participants->map(function($user) {
            return 'private-'.$user->getRouteKey();
        })->toArray();

        // The data we send with the push
        $pushedData = [
            'ad_id' => $ad->getId(),
            'update_route' => route('ads.show.fragment', $ad->getId()),
        ];

        // Send the message back to users discussing this ad
        LaravelPusher::trigger($channelsToNotify, 'updated_ad', $pushedData);
    }
}
