<?php namespace Sneefr\Services\ActivityFeed;

use App;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent as DataBag;
use Sneefr\Models\Follow;
use Sneefr\Models\User;

/**
 * Create and populate activity feeds by aggregating data from item providers.
 */
class ActivityFeedBuilder
{
    /**
     * The identifier of the person the feed must be built for.
     *
     * @var int
     */
    protected $person;

    /**
     * The registered item providers.
     *
     * @var array
     */
    protected $itemProviders = [
        'AdProvider',              // provides ‘ad’ items
        'ShopAdsProvider',              // provides ‘ad’ items
        // TODO: reimplement DealProvider
        'DiscussionProvider',      // provides 'discussions' items
        'SearchProvider',          // provides ‘search’ items
        'FollowedPersonProvider',  // provides ‘followed-person’ items
        'FollowedPlaceProvider',   // provides ‘place’ items
    ];

    /**
     * A data container that is shared among providers.
     *
     * @var \Illuminate\Support\Fluent
     */
    protected $data;

    /**
     * Create a new instance of an activity feed builder.
     */
    public function __construct()
    {
        $this->data = new DataBag;
    }

    /**
     * A convenience method that is run before calling any item provider.
     *
     * It is used to populate the shared data container with some initial data.
     *
     * @return void
     */
    protected function init()
    {
        // This will retrieve the IDs of the people that
        // the authenticated person is following.
        $followedIds = User::find($this->data['person'])->following()->users()->identifiers();
        
        $this->with('followedIds', $followedIds);

        $followedShopIds = Follow::where('user_id', $this->data['person'])
            ->where('followable_type', 'shop')
            ->get()
            ->pluck('followable_id')
            ->all();

        $this->with('followedShopIds', $followedShopIds);
    }

    /**
     * Build an activity feed.
     *
     * @return \Sneefr\Services\ActivityFeed\ActivityFeed
     */
    public function make()
    {
        $this->init();

        $items = $this->aggregateItems();

        $sorted = $this->sortItems($items);

        $feed = new ActivityFeed;

        return $feed->addItems($sorted);
    }

    /**
     * Add data to the builder’s shared data container.
     *
     * @param  string  $key
     * @param  mixed   $data
     *
     * @return self
     */
    public function with($key, $data)
    {
        $this->data[$key] = $data;

        return $this;
    }

    /**
     * Collect the items from the registered providers together.
     *
     * @return \Illuminate\Support\Collection  A collection of ActivityFeedItem objects
     */
    protected function aggregateItems()
    {
        $items = new Collection;

        foreach ($this->itemProviders as $provider) {

            $provider = $this->getProvider($provider)->with($this->data);

            $items = $items->merge($provider->get());
        }

        return $items;
    }

    /**
     * Get an instance of a given item provider.
     *
     * @param  string  $provider  The name of the provider
     *
     * @return \Sneefr\Services\ActivityFeed\Providers\AbstractProvider
     */
    protected function getProvider($provider)
    {
        // Build the fully qualified class name and create
        // an instance of it via the container.
        $class = __NAMESPACE__.'\\Providers\\'.$provider;

        return App::make($class);
    }

    /**
     * Sort the given collection of feed items.
     *
     * @param  \Illuminate\Support\Collection  $items  A collection of
     *                                                 ActivityFeedItem objects
     *
     * @return \Illuminate\Support\Collection  The sorted collection
     */
    protected function sortItems(Collection $items)
    {
        return $items->sort(function ($a, $b) {
            return $a->sortingValue <= $b->sortingValue;
        });
    }
}
