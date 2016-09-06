<?php namespace Sneefr\Services\ActivityFeed\Providers;

use Sneefr\Models\Ad as AdModel;
use Sneefr\Models\Follow;

/**
 * Provide activity feed items for published ads.
 */
class ShopAdsProvider extends AbstractProvider
{
    /**
     * The type of item that is provided by the class.
     *
     * @var string
     */
    protected $providedType = 'shop_ad';

    /**
     * Get items from the provider.
     *
     * @return \Illuminate\Support\Collection  A collection of ActivityFeedItem objects
     */
    public function get()
    {
        // Start by getting the items.
        $ads = $this->getAdsFromShops();

        $items = $this->itemizeCollection($ads);

        // Then, set metadata on them.
        $this->setLikes($items);
        $this->setSupplyReasons($items);

        return $items;
    }

    /**
     * Retrieve elements directly created by the shops.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getAdsFromShops()
    {
        $followedhopIds = Follow::where('user_id', $this->data('person'))
            ->where('followable_type', 'shop')
            ->lists('followable_id')->all();

        return AdModel::whereIn('shop_id', $followedhopIds)
            ->has('shop')
            ->with('shop')
            ->latest()
            ->get();
    }
}
