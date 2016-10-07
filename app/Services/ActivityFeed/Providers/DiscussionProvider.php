<?php

namespace Sneefr\Services\ActivityFeed\Providers;

// Interfaces.
use Sneefr\Repositories\Discussion\DiscussionRepository;
use Sneefr\Services\ActivityFeed\ActivityFeedItem;

/**
 * Provide activity feed items for discussions.
 */
class DiscussionProvider extends AbstractProvider
{
    /**
     * The type of item that is provided by the class.
     *
     * @var string
     */
    protected $providedType = 'discussion';

    /**
     * A repository of discussions.
     *
     * @var \Sneefr\Repositories\Discussion\DiscussionRepository
     */
    protected $repository;

    /**
     * Create a new instance of the feed item provider.
     *
     * @param \Sneefr\Repositories\Discussion\DiscussionRepository  $repository
     */
    public function __construct(DiscussionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get items from the provider.
     *
     * @return array  An array of ActivityFeedItem objects
     */
    public function get()
    {
        // Make sure that we won’t retrieve discussions involving
        // the person we build the activity feed for.
        $identifiers = collect($this->data('followedIds'))->except($this->data('person'))->unique()->toArray();

        // Get discussions from the followed people.
        $discussions = $this->repository->of($identifiers);

        $discussions = $discussions->filter(function($item){
            return is_null($item->participants[0]->deleted_at) && is_null($item->participants[1]->deleted_at);
        });

        $items = $this->itemizeCollection($discussions);

        // Set metadata on the items.
        $this->setLikes($items);
        $this->setSupplyReasons($items);

        return $items;
    }

    /**
     * Try to identify the item owner from a given piece of data.
     *
     * @param  mixed  $data
     *
     * @throws \Exception if no owner can be determined
     *
     * @return mixed
     */
    protected function findOwner($data)
    {
        // Extract the identifiers of the discussion’s participants.
        $participantIds = $data->participants->pluck('id')->all();

        // Find the ones that are among followed persons.
        $followedParticipantIds = array_intersect(
            $participantIds,
            $this->data('followedIds')
        );

        // Sort the identifiers and reset the numeric keys.
        sort($followedParticipantIds);

        if (count($followedParticipantIds)) {
            // Return the first person among followed participants.
            return $data->participants->find($followedParticipantIds[0]);
        }

        throw new Exception('Cannot identify owner of activity feed item');
    }

    /**
     * Find a sorting value from a given piece of data.
     *
     * This value can be used as a sorting criterion.
     *
     * @param  mixed  $data
     *
     * @return mixed
     */
    protected function findSortingValueFrom($data)
    {
        return $data->created_at->getTimestamp();
    }

    /**
     * Determine a supply reason using contextual info.
     *
     * @param  \Sneefr\Services\ActivityFeed\ActivityFeedItem  $item
     * @param  array  $info
     *
     * @return int
     *
     * @throws \Exception if it is not possible to determine a supply reason
     */
    protected function determineSupplyReason(ActivityFeedItem $item, array $info)
    {
        // Extract the identifiers of the discussion’s participants.
        $participantIds = $item->value->participants->pluck('id')->all();

        // The discussion involves a related person.
        if (array_intersect($participantIds, $info['followedIds'])) {
            return ActivityFeedItem::REASON_INVOLVING_RELATIONSHIP;
        }

        throw new Exception("Cannot determine why item was retrieved");
    }
}
