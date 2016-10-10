<?php

namespace Sneefr\Services\ActivityFeed\Providers;

use Illuminate\Support\Collection;
use Sneefr\Models\Follow as RelationshipModel;

/**
 * Provide activity feed items for followed persons.
 */
class FollowedPersonProvider extends AbstractProvider
{
    /**
     * The type of item that is provided by the class.
     *
     * @var string
     */
    protected $providedType = 'followed_person';

    /**
     * Get items from the provider.
     *
     * @return \Illuminate\Support\Collection  A collection of ActivityFeedItem objects
     */
    public function get()
    {
        // Start by getting the items.
        $relationships = $this->getRelationshipsFromUsers();

        $items = $this->itemizeCollection($relationships);

        // TODO: implement retrieval of likes + supply reason.

        return $items;
    }

    /**
     * Retrieve elements directly created by users.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRelationshipsFromUsers()
    {
        $userIds = array_merge(
            (array) $this->data('followedIds'),
            (array) $this->data('person')
        );

        // Get the relationships (of any type) where either
        // the person or any of the followed people is the
        // initiator of the relationship.
        $results = RelationshipModel::distinct()
            ->whereIn('user_id', $userIds)
            ->whereNotIn('followable_id', (array) $this->data('person'))
            ->where('followable_type', 'user')
            ->with(['initiator', 'user'])
            ->get();

        $results = $this->flattenConsecutiveFollowsToTheSameTarget($results);

        return $results->reject(function($item) {
            return is_null($item->user) ||  is_null($item->initiator);
        });
    }

    /**
     * Merge consecutive relationships targeting the same person.
     *
     * Each relationship resulting from a merge will contain an
     * ‘initiators’ property storing a Collection of models
     * for all the persons who follow the target person.
     *
     * @param  \Illuminate\Support\Collection  $relationships
     *
     * @return \Illuminate\Support\Collection
     */
    protected function flattenConsecutiveFollowsToTheSameTarget(Collection $relationships)
    {
        $initiators = new Collection;
        $results = [];

        foreach ($relationships as $i => $relationship) {

            // Store the model of the current following person.
            $initiators[] = $relationship->initiator;

            if ($this->nextRelationshipTargetsSameUser($relationships, $i)) {
                continue;
            }

            // If several persons follow the current target user,
            // we store this list of people on the relationship.
            if (count($initiators) > 1) {
                $relationship->initiators = $initiators;
            }

            // Reinitialize the list of following persons.
            $initiators = new Collection;

            $results[] = $relationship;
        }

        return collect($results);
    }

    /**
     * Checks if a given relationship and the one immediately
     * following it target the same person.
     *
     * @param  \Illuminate\Support\Collection  $relationships  A list of relationships
     * @param  int  $index  The position of the base relationship
     *
     * @return bool
     */
    protected function nextRelationshipTargetsSameUser(Collection $relationships, int $index)
    {
        $currentRelationship = $relationships->get($index);
        $nextRelationship    = $relationships->get($index + 1);

        if (
            is_null($nextRelationship) ||
            $currentRelationship->to_user_id !== $nextRelationship->to_user_id
        ) {
            return false;
        }

        return true;
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
        // Was the relationship initiated by the person we build the feed for?
        // If so, then this person is indeed the owner of this item.
        if ($this->data('person') === $data->from_user_id) {
            return $data->initiator;
        }

        // Otherwise the item’s owner is the initiator of the relationship.
        return $data->initiator;
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
}
