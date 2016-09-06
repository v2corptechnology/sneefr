<?php namespace Sneefr\Services\ActivityFeed\Providers;

use Sneefr\Models\Ad as AdModel;

/**
 * Provide activity feed items for published ads.
 */
class AdProvider extends AbstractProvider
{
    /**
     * Get items from the provider.
     *
     * @return \Illuminate\Support\Collection  A collection of ActivityFeedItem objects
     */
    public function get()
    {
        // Start by getting the items.
        $ads = $this->getAdsFromUsers();
        $ads = $ads->merge($this->getAdsFromFriendLikes());

        $items = $this->itemizeCollection($ads);

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
    protected function getAdsFromUsers()
    {
        // Ensure that, when getting ads created by followed persons,
        // we only return those that are not hidden for ‘friends’.
        // That way, we will not disclose anything by accident.
        $createdByFollowedPersons = function ($query) {
            $query->whereIn('user_id', $this->data('followedIds'))
                ->where('is_hidden_from_friends', 0)
                ->whereNull('shop_id');
        };

        // We will retrieve both ads created by the defined person
        // and those created by people that the defined person
        // follows.
        $query = AdModel::where('user_id', $this->data('person'))
            ->whereNull('shop_id')
            ->orWhere($createdByFollowedPersons)
            ->with(['user', 'tags', 'likes.user']);

        return $query->get();
    }

    /**
     * Retrieve elements via likes.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getAdsFromFriendLikes()
    {
        // We also need to retrieve a given maximum amount of ads
        // that have been liked by followed persons. Selection
        // will be made randomly from the results we’ll get.
        $query = AdModel::whereHas('likes', function ($subquery) {
            $subquery->whereIn('user_id', $this->data('followedIds'));
        })
        ->with(['user', 'tags', 'likes.user'])
        ->take(5)
        ->orderByRandom();

        return $query->get();
    }
}
