<?php

namespace Sneefr\Repositories\Place;

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
}
