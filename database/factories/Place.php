<?php

use Sneefr\Models\Place;

/**
 * Default factory.
 */
$factory->define(Place::class, function ($faker) {
    return [
        'latitude'         => $faker->latitude,
        'longitude'        => $faker->longitude,
        'service_place_id' => $faker->randomNumber,
    ];
});
