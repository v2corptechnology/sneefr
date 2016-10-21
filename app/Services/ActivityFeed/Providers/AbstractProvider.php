<?php

namespace Sneefr\Services\ActivityFeed\Providers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Sneefr\Services\ActivityFeed\ActivityFeedItem;

/**
 * Base abstract class for activity feed item providers.
 */
abstract class AbstractProvider
{
    /**
     * The type of item that is provided by the class.
     *
     * @var string
     */
    protected $providedType;

    /**
     * A data container that is shared among providers.
     *
     * @var \Illuminate\Support\Fluent
     */
    protected $data;

    /**
     * Get items from the provider.
     *
     * @return array  An array of ActivityFeedItem objects
     */
    abstract public function get();

    /**
     * Get the type of item that is provided by the class.
     *
     * @return string
     */
    public function providedType()
    {
        if (isset($this->providedType)) {
            return $this->providedType;
        }

        // If no type is specified yet, retrieve it from the class name.
        $classBaseName = basename(str_replace('\\', '/', get_class($this)));

        $type = strtolower(str_replace('Provider', '', $classBaseName));

        return $this->providedType = $type;
    }

    /**
     * Add data to the shared data container.
     *
     * @param  array|\Illuminate\Support\Fluent  $dataBag
     *
     * @return self
     */
    public function with($dataBag)
    {
        // If two arguments were passed to the method, it means
        // that we specified a key and a value. We recursively
        // call the method again with the correct structure.
        if (func_num_args() === 2) {
            return $this->with([func_get_arg(0) => func_get_arg(1)]);
        }

        // If the provider stores no data bag yet, let’s store all the
        // data in one go in order to store a reference to the object.
        if (is_null($this->data)) {
            $this->data = $dataBag;

            return $this;
        }

        // Loop on the data and copy it to the provider’s data bag.
        foreach ((array) $dataBag as $key => $value) {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Get data from the shared data container.
     *
     * @param  string  $key  The key to retrieve data for
     *
     * @return mixed
     */
    protected function data($key)
    {
        if (!$this->hasData($key)) {
            throw new Exception(__CLASS__." does not contain any data for [$key]");
        }

        return $this->data[$key];
    }

    /**
     * Determine if the shared data container stores data for a given key.
     *
     * @param  string  $key  The key to check
     *
     * @return bool
     */
    protected function hasData($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * Convert a list of items to a collection of activity feed items.
     *
     * @param  mixed  $collection
     *
     * @return \Illuminate\Support\Collection  A collection of ActivityFeedItem objects
     */
    protected function itemizeCollection($collection)
    {
        $items = [];

        foreach ($collection as $item) {
            $items[] = $this->itemize($item);
        }

        return new Collection($items);
    }

    /**
     * Encapsulate a given piece of data in an ActivityFeedItem value object.
     *
     * @param  mixed  $data
     *
     * @return \Sneefr\Services\ActivityFeed\ActivityFeedItem
     */
    protected function itemize($data)
    {
        $item = new ActivityFeedItem;

        $item->type         = $this->providedType();
        $item->id           = $item->type.'-'.$this->identifyFrom($data);
        $item->value        = $data;
        $item->owner        = $this->findOwner($data);
        $item->sortingValue = $this->findSortingValueFrom($data);
        $item->likePayload  = $this->likePayload($item, $data);

        return $item;
    }

    /**
     * Get an identifier from a given piece of data.
     *
     * @param  mixed  $data
     *
     * @throws \Exception if no identifier can be determined
     *
     * @return mixed
     */
    protected function identifyFrom($data)
    {
        if ($data instanceof Model && $data->id) {
            return $data->id;
        }

        throw new Exception('Cannot identify activity feed item');
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
        if ($data instanceof Model && isset($data->user_id)) {
            return $data->user;
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
     * @throws \Exception if no identifier can be determined
     *
     * @return mixed
     */
    protected function findSortingValueFrom($data)
    {
        if ($data instanceof Model) {
            return $data->updated_at->getTimestamp();
        }

        throw new Exception('Cannot find a sorting value for feed item');
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
        // Likes have already been retrieved when getting the ads.
        // As a result, we can simply extract their data from
        // what we already have in our collection of items.
        foreach ($items as $item) {
            $item->likes = $this->extractLikesData($item);
        }
    }

    /**
     * Extract likes data from an activity feed item.
     *
     * @param  \Sneefr\Services\ActivityFeed\ActivityFeedItem  $item
     *
     * @return \Illuminate\Support\Collection  A collection with data for each extracted like
     */
    protected function extractLikesData(ActivityFeedItem $item)
    {
        $followedIds = $this->data('followedIds');

        $likes = new Collection;

        foreach ($item->value->likes as $like) {
            // We subset the data of likes in order to get a
            // standardized set of keys for each item types.
            $likes[] = [
                'id'         => $like->id,
                'user'       => $like->user,
                'personId'   => $like->user->id,
                'givenName'  => $like->user->present()->givenName(),
                'surname'    => $like->user->present()->surname(),
                'isFollowed' => in_array($like->user->id, $followedIds),
            ];
        }

        // Remove likes from the contained model itself.
        unset($item->value->likes);

        return $likes;
    }

    /**
     * Determine and set the supply reason on each of the given items.
     *
     * @param \Illuminate\Support\Collection  $items
     *
     * @return void
     */
    protected function setSupplyReasons(Collection $items)
    {
        $info = [
            'personId'        => $this->data('person'),
            'followedIds'     => $this->data('followedIds'),
            'followedShopIds' => $this->data('followedShopIds'),
        ];

        foreach ($items as $item) {
            $item->supplyReason = $this->determineSupplyReason($item, $info);
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

        // The item is owned by the person.
        if ($ownerId == $info['personId']) {
            return ActivityFeedItem::REASON_OWNED;
        }

        // The item is owned by a related person.
        if (in_array($ownerId, $info['followedIds'])) {
            return ActivityFeedItem::REASON_OWNED_BY_RELATIONSHIP;
        }

        // The item is owned by a related person.
        if (in_array($ownerId, $info['followedIds'])) {
            return ActivityFeedItem::REASON_OWNED_BY_RELATIONSHIP;
        }

        // The item is owned by a followed shop.
        if ($item->value->shop_id && in_array($item->value->shop_id, $info['followedShopIds'])) {
            return ActivityFeedItem::REASON_OWNED_BY_RELATIONSHIP;
        }

        // The item is owned by a followed shop
        /*if (in_array($shopId, $info['followedShopIds'])) {
            return ActivityFeedItem::REASON_OWNED_BY_RELATIONSHIP;
        }*/

        // Get the identifiers of persons who liked this item.
        $likers = $item->likes->pluck('personId');

        // The item is liked by a related person.
        if (count($likers->intersect($info['followedIds']))) {
            return ActivityFeedItem::REASON_LIKED_BY_RELATIONSHIP;
        }

        throw new Exception("Cannot determine why item was retrieved");
    }

    /**
     * Generate the like/unlike payload passed.
     *
     * @param \Sneefr\Services\ActivityFeed\ActivityFeedItem $item
     * @param                                                $data
     *
     * @return string
     * @throws \Exception
     */
    protected function likePayload(ActivityFeedItem $item, $data)
    {
        $payload = [
            'type' => $item->type,
            'id' => $this->identifyFrom($data)
        ];

        $payload = json_encode($payload);

        return (string) app('encrypter')->encrypt($payload);
    }
}
