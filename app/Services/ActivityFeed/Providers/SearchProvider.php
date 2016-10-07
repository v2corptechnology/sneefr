<?php

namespace Sneefr\Services\ActivityFeed\Providers;

use Sneefr\Models\Search as SearchModel;
use Sneefr\Repositories\Search\SearchRepository;

/**
 * Provide activity feed items for searches.
 */
class SearchProvider extends AbstractProvider
{
    /**
     * A repository of searches.
     *
     * @var \Sneefr\Repositories\Search\SearchRepository
     */
    protected $repository;

    /**
     * Create a new instance of the feed item provider.
     *
     * @param \Sneefr\Repositories\Search\SearchRepository  $repository
     */
    public function __construct(SearchRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get items from the provider.
     *
     * @return \Illuminate\Support\Collection  A collection of ActivityFeedItem objects
     */
    public function get()
    {
        // Start by getting the items.
        $searches = $this->getSearchesFromUsers();
        $searches = $searches->merge($this->getSearchesFromFriendLikes());

        $items = $this->itemizeCollection($searches);

        // Then, set metadata on them.
        $this->setLikes($items);
        $this->setSupplyReasons($items);

        return $items;
    }

    /**
     * Retrieve elements directly created by users.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSearchesFromUsers()
    {
        // Retrieve searches that have been made by followed
        // persons or by the authenticated person herself.
        $searches = $this->repository->getFriendSearchesFor(
            $this->data('person'),
            $this->data('followedIds')
        );

        return $searches;
    }

    /**
     * Retrieve elements via likes.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getSearchesFromFriendLikes()
    {
        // Get searches that have been liked by followed persons.
        $query = SearchModel::whereHas('likes', function ($subquery) {
            $subquery->whereIn('user_id', $this->data('followedIds'));
        })
        ->with(['user', 'likes.user']);

        return $query->get();
    }
}
