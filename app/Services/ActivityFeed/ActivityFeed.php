<?php

namespace Sneefr\Services\ActivityFeed;

// Exceptions.
use Countable;
use Illuminate\Support\Collection;
use IteratorAggregate;
use OutOfRangeException;

// Interfaces.

// Project classes.

// Third-party dependencies.

/**
 * Represents a feed of content.
 */
class ActivityFeed implements Countable, IteratorAggregate
{
    /**
     * The contents of the activity feed.
     *
     * This is a collection of ActivityFeedItem objects.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $items;

    /**
     * The amount of items that are being shown by page.
     *
     * @var int
     */
    protected $itemsPerPage = 30;

    /**
     * Create a new activity feed for a given person.
     * The argument can either be an identifier or a Person instance.
     *
     * @param  int $userId
     *
     * @return static
     */
    public static function of($userId)
    {

        return (new ActivityFeedBuilder)->with('person', $userId)->make();
    }

    /**
     * Add the given collection of items to the feed.
     *
     * @param  \Illuminate\Support\Collection  $items  A collection of ActivityFeedItem objects
     *
     * @return self
     */
    public function addItems(Collection $items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Determine if there are enough items to split into multiple pages.
     *
     * @return bool
     */
    public function hasPages()
    {
        if (count($this->items) > $this->itemsPerPage) {
            return true;
        }

        return false;
    }

    /**
     * Determine if a given page exists.
     *
     * @param  int  $page  A page number
     *
     * @return bool
     */
    public function hasPage($page)
    {
        $page = $this->validatePageNumber($page);

        // In order to have something on a given page, the total amount
        // of items in the feed must be bigger than the amount of items
        // that is necessary to go to the end of the previous page.
        // Example for page 3 with 23 items: 23 > (2 * 10)
        return (count($this->items) > (($page - 1) * $this->itemsPerPage));
    }

    /**
     * Get the items for a given page.
     *
     * If the page does not exist, an empty collection will be returned.
     *
     * @param  int  $page  A page number
     *
     * @return \Illuminate\Support\Collection  A collection of ActivityFeedItem objects
     */
    public function page($page)
    {
        $page = $this->validatePageNumber($page);

        $offset = ($page - 1) * $this->itemsPerPage;

        return $this->items->slice($offset, $this->itemsPerPage);
    }

    /**
     * Count the number of items in the feed.
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->items->getIterator();
    }

    /**
     * Ensure that a page number is valid.
     *
     * @param  int  $page
     *
     * @throws \OutOfRangeException if the page number is invalid
     *
     * @return int  The validated page number
     */
    protected function validatePageNumber($page)
    {
        $page = floor($page);

        if ($page < 1) {
            throw new OutOfRangeException("The minimum valid page number is 1, [$page] given");
        }

        return $page;
    }
}
