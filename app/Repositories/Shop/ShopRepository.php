<?php

namespace Sneefr\Repositories\Shop;

use Illuminate\Support\Collection;
use Sneefr\Models\Shop;

interface ShopRepository
{
    /**
     * Get a shop by it's slug.
     *
     * @param string $slug
     *
     * @return null|\Sneefr\Models\Shop
     */
    public function bySlug(string $slug);
}
