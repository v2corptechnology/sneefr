<?php namespace Sneefr\Listeners;

use Sneefr\Events\MessageWasSent;
use Sneefr\Repositories\Discussion\DiscussionRepository;

class UpdateDiscussionStatus
{
    /**
     * @var \Sneefr\Repositories\Discussion\DiscussionRepository
     */
    private $discussionRepository;

    /**
     * Create the event handler.
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
     * @param \Sneefr\Events\MessageWasSent $event
     */
    public function handle(MessageWasSent $event)
    {
        $discussion = $this->discussionRepository->between($event->sender->getId(), $event->receiver->getId());

        // Update discussions last edition date
        $discussion->touch();
    }

}
