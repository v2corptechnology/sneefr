<?php

namespace Sneefr\Repositories\Ad;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Sneefr\Models\Ad;

class EloquentAdRepository implements AdRepository
{
    /**
     * Retrieve a specific ad.
     *
     * @param int $adId
     *
     * @return \Sneefr\Models\Ad
     *
     * @throws  \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function find(int $adId) : Ad
    {
        return Ad::findOrFail($adId);
    }

    /**
     * Get latest ads.
     *
     * @param int $limit
     *
     * @return \Illuminate\Support\Collection
     */
    public function latest(int $limit = 3) : Collection
    {
        return Ad::latest()->take($limit)->get();
    }

    /**
     * Get the ads of those users.
     *
     * @param int|array $userIds The user identifiers we fetch the ads for
     * @param string    $filter  (optional) The filter we optionally use
     *
     * @return \Illuminate\Support\Collection A collection of Ad models
     */
    public function of($userIds, string $filter = null) : Collection
    {
        return Ad::whereIn('user_id', (array) $userIds)
            ->whereNull('shop_id')
            ->latest()
            ->search($filter)
            ->get();
    }

    /**
     * Get ads sold by those users.
     *
     * @param \int[] ...$userIds
     *
     * @return \Illuminate\Support\Collection A collection of Ad models
     */
    public function soldOf(int ...$userIds) : Collection
    {
        return Ad::whereIn('user_id', $userIds)->whereNull('shop_id')->sold()->get();
    }

    /**
     * Get ads in that category.
     *
     * @param \int[] ...$categories
     *
     * @return \Illuminate\Support\Collection
     */
    public function byCategory(int ...$categories) : Collection
    {
        return Ad::whereIn('category_id', $categories)->get();
    }
}
