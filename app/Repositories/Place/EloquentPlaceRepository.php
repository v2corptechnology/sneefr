<?php

namespace Sneefr\Repositories\Place;

use Illuminate\Support\Collection;
use Sneefr\Models\Place;

class EloquentPlaceRepository implements PlaceRepository
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
    public function followers(int $placeId) : Collection
    {
        return Place::findOrFail($placeId)->followers;
    }

    /**
     * Get places in a sequential order.
     *
     * @param array $sequence
     *
     * @return \Illuminate\Support\Collection
     */
    public function bySequence(array $sequence) : Collection
    {
        $places = Place::whereIn('id', $sequence)->get();

        // Sort manually since ORDER BY FIELD doen't exists in sqlite
        // $query->orderByRaw(DB::raw("FIELD(id, $orderedIds)"));
        return $places->sort(function ($placeA, $placeB) use ($sequence) {
            $posA = array_search($placeA->id, $sequence);
            $posB = array_search($placeB->id, $sequence);
            return $posA - $posB;
        });
    }
}
