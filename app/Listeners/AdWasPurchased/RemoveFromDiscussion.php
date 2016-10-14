<?php

namespace Sneefr\Listeners\AdWasPurchased;

use Illuminate\Contracts\Queue\ShouldQueue;
use Sneefr\Events\AdWasPurchased;
use Sneefr\Repositories\Discussion\DiscussionRepository;

class RemoveFromDiscussion implements ShouldQueue
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
     * @param  AdWasPurchased $event
     *
     * @return void
     */
    public function handle(AdWasPurchased $event)
    {
        // Fetch the discussion between the two users
        $discussion = $this->discussionRepository->between($event->ad->seller->getId(), $event->buyer->getId());

        // Remove the ad from the discussion
        if ($discussion) {
            $discussion->removeAd($event->ad->getId());
        }
    }
}
