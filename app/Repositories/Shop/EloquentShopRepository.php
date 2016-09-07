<?php namespace Sneefr\Repositories\Shop;

use Illuminate\Support\Collection;
use Sneefr\Models\Shop;

class EloquentShopRepository implements ShopRepository
{
    /**
     * Get a shop by it's slug.
     *
     * @param string $slug
     *
     * @return null|\Sneefr\Models\Shop
     */
    public function bySlug(string $slug)
    {
        return Shop::where('slug', $slug)->first();
    }
}
