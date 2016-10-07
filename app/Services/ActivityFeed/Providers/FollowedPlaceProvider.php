<?php

namespace Sneefr\Services\ActivityFeed\Providers;
use Sneefr\Models\User;

/**
 * Provide activity feed items for followed places.
 */
class FollowedPlaceProvider extends AbstractProvider
{
    /**
     * The type of item that is provided by the class.
     *
     * @var string
     */
    protected $providedType = 'followed_place';

    /**
     * Get items from the provider.
     *
     * @return \Illuminate\Support\Collection  A collection of ActivityFeedItem objects
     */
    public function get()
    {
        // Start by getting the items.
        $relationships = $this->getPlacesFromUsers();

        $items = $this->itemizeCollection($relationships);

        // Set metadata on the items.
        $this->setLikes($items);

        // TODO: implement retrieval of supply reason.

        return $items;
    }

    /**
     * Retrieve elements directly created by users.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPlacesFromUsers()
    {
        $userIds = array_merge(
            (array) $this->data('followedIds'),
            (array) $this->data('person')
        );

        // Get the places followed by either the
        // person or any of the followed people.
        // Return only the Place model
        return User::whereIn('id', $userIds)
            ->with('places.followers', 'places.likes')
            ->get()
            ->pluck('places')
            ->collapse()
            ->unique('id');
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
        return $data->pivot->created_at->getTimestamp();
    }
}
