<?php namespace Sneefr\Repositories\Shop;

use Illuminate\Support\Collection;
use Sneefr\Models\Shop;

class EloquentShopRepository implements ShopRepository
{
    /**
     * Get the shops that have the biggest number of ads.
     *
     * @param int   $limit   The number of shops to retrieve.
     * @param array $shopIds (optional) The shop identifiers we want to limit.
     *
     * @return \Illuminate\Support\Collection
     */
    public function biggestSellers(int $limit = 3, array $shopIds = []) : Collection
    {
        $query = Shop::with('ads');

        if ($shopIds) {
            $query->whereIn('id', $shopIds);
        }

        $results = $query->get()->sortByDesc(function ($shop) {
            return count($shop->ads);
        });

        if ($limit) {
            return $results->take($limit);
        }

        return $results;
    }

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
