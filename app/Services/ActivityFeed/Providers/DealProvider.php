<?php

namespace Sneefr\Services\ActivityFeed\Providers;

use Exception;
use Illuminate\Support\Collection;
use Sneefr\Models\Ad as AdModel;
use Sneefr\Services\ActivityFeed\ActivityFeedItem;

/**
 * Provide activity feed items for concluded sales.
 */
class DealProvider extends AbstractProvider
{
    /**
     * Get items from the provider.
     *
     * @return \Illuminate\Support\Collection  A collection of ActivityFeedItem objects
     */
    public function get()
    {
        // Start by getting the items.
        $deals = $this->getDealsFromUsers();
        // $deals = $deals->merge($this->getDealsFromFriendLikes());

        $items = $this->itemizeCollection($deals);

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
    protected function getDealsFromUsers()
    {
        $person = $this->data('person');
        $followedPersons = $this->data('followedIds');

        $soldOrBoughtByPerson = function ($query) use ($person)  {
            $query->where('user_id', $person)
                ->orWhere('sold_to', $person);
        };

        // Ensure that, when getting ads sold by/to followed persons,
        // we only return those that are not hidden for ‘friends’.
        // That way, we will not disclose anything by accident.
        $soldOrBoughtByFollowedPersons = function ($query) {

            $query->where(function ($subquery)  {
                $subquery->whereIn('user_id', $this->data('followedIds'))
                    ->orWhereIn('sold_to', $this->data('followedIds'));
            })
            ->where('is_hidden_from_friends', 0);
        };

        // We will retrieve both ads sold by/to the defined person
        // and those sold by/to people that the defined person
        // follows.
        $query = AdModel::onlySold()
            ->with(['user', 'tags', 'likes.user'])
            ->where(function ($query) use ($soldOrBoughtByPerson, $soldOrBoughtByFollowedPersons)  {

                $query->where($soldOrBoughtByPerson)
                    ->orWhere($soldOrBoughtByFollowedPersons);
            });

        return $query->get();
    }

    /**
     * Get an identifier from a given piece of data.
     *
     * @param  mixed  $data
     *
     * @return mixed
     */
    protected function identifyFrom($data)
    {
        return $data->ad_id;
    }

    /**
     * Retrieve and set likes related to each of the given items.
     *
     * @param \Illuminate\Support\Collection  $items
     *
     * @return void
     */
    protected function setLikes(Collection $items)
    {
        // From all the likes that the retrieved ads may have,
        // only those that have been created after the sale
        // will be considered as likes for the deals.
        foreach ($items as $item) {

            $saleDate = $item->value->deleted_at;

            // Reject likes created before the sale.
            $item->value->likes = $item->value->likes->reject(function ($like) use ($saleDate) {
                return $like->created_at < $saleDate;
            });

            $item->likes = $this->extractLikesData($item);
        }
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
        $ownerId = $item->value->user_id;
        $buyerId = $item->value->sold_to;

        // We test different scenarios, from the ones having
        // the highest priority to those having the lowest.

        // The item is owned by the person.
        if ($ownerId == $info['personId']) {
            return ActivityFeedItem::REASON_OWNED;
        }

        // The item is owned by a related person.
        if (in_array($ownerId, $info['followedIds'])) {
            return ActivityFeedItem::REASON_OWNED_BY_RELATIONSHIP;
        }

        // The item is bought by the person.
        if ($buyerId == $info['personId']) {
            return ActivityFeedItem::REASON_INVOLVED;
        }

        // The item is bought by a related person.
        if (in_array($buyerId, $info['followedIds'])) {
            return ActivityFeedItem::REASON_INVOLVING_RELATIONSHIP;
        }

        // Get the identifiers of persons who liked this item.
        $likers = $item->likes->pluck('personId');

        // The item is liked by a related person.
        if (count($likers->intersect($info['followedIds']))) {
            return ActivityFeedItem::REASON_LIKED_BY_RELATIONSHIP;
        }

        throw new Exception("Cannot determine why item was retrieved");
    }
}
