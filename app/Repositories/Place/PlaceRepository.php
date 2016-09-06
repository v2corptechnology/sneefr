<?php namespace Sneefr\Repositories\Place;

use Illuminate\Support\Collection;

interface PlaceRepository
{
    /**
     * Retrieve place's followers.
     *
     * @param int $placeId
     *
     * @return \Illuminate\Support\Collection
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function followers(int $placeId) : Collection;

    /**
     * Get places in a sequential order.
     *
     * @param array $sequence
     *
     * @return \Illuminate\Support\Collection
     */
    public function bySequence(array $sequence) : Collection;

    /**
     * Get the shops that have the biggest number of ads.
     *
     * @param int   $limit   The number of sellers to retrieve.
     * @param array $placeIds (optional) The place identifiers we want to limit.
     *
     * @return \Illuminate\Support\Collection
     */
    public function biggestSellers(int $limit = 3, array $placeIds = []) : Collection;
}
