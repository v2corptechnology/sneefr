<?php

namespace Sneefr\Repositories\Ad;

use Illuminate\Support\Collection;
use Sneefr\Models\Ad;

interface AdRepository
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
    public function find(int $adId) : Ad;

    /**
     * Get latest ads.
     *
     * @param int $limit
     *
     * @return \Illuminate\Support\Collection
     */
    public function latest(int $limit = 3) : Collection;

    /**
     * Get the ads of those users.
     *
     * @param int|array $userIds The user identifiers we fetch the ads for
     * @param string    $filter  (optional) The filter we optionally use
     *
     * @return \Illuminate\Support\Collection A collection of Ad models
     */
    public function of($userIds, string $filter = null) : Collection;

    /**
     * Get ads sold by those users.
     *
     * @param \int[] ...$userIds
     *
     * @return \Illuminate\Support\Collection A collection of Ad models
     */
    public function soldOf(int ...$userIds) : Collection;

    /**
     * Get ads in that category.
     *
     * @param \int[] ...$categories
     *
     * @return \Illuminate\Support\Collection
     */
    public function byCategory(int ...$categories) : Collection;
}
