<?php

namespace Sneefr\Services\ActivityFeed;

use Illuminate\Support\Collection;

/**
 * A generic container for a feed item, allowing to manipulate it without
 * having to deal with the specifics of the encapsulated data.
 */
class ActivityFeedItem
{
    // The item belongs to the person the
    // activity feed is related to.
    const REASON_OWNED = 1;

    // The item is related to the person the
    // activity feed is related to.
    const REASON_INVOLVED = 2;

    // The item is owned by a related person.
    const REASON_OWNED_BY_RELATIONSHIP = 4;

    // The item involves a related person.
    const REASON_INVOLVING_RELATIONSHIP = 8;

    // The item is liked by a related person.
    const REASON_LIKED_BY_RELATIONSHIP = 16;

    /**
     * A unique identifier for the feed item.
     *
     * @var mixed
     */
    public $id;

    /**
     * The type of item.
     *
     * @var string
     */
    public $type = 'default';

    /**
     * The data that is encapsulated by the item.
     *
     * @var mixed
     */
    public $value;

    /**
     * The person ‘owning’ the item.
     *
     * @var \Sneefr\Models\User
     */
    public $owner;

    /**
     * The likes that are related to the item.
     *
     * @var \Illuminate\Support\Collection
     */
    public $likes;

    /**
     * A value that can be used as a sorting criterion.
     *
     * @var mixed
     */
    public $sortingValue;

    /**
     * The reason why the item has been provided.
     * See class constants for possible values.
     *
     * @var string
     */
    public $supplyReason;

    /**
     * The payload value used to identify a like action.
     *
     * @var string
     */
    public $likePayload;

    /**
     * Create a new activity feed item.
     */
    public function __construct()
    {
        $this->likes = new Collection;
    }
}
