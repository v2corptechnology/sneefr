<?php

use FactoryUtilities as Utils;
use Sneefr\Models\Shop;

/**
 * Default factory.
 */
$factory->define(Shop::class, function ($faker) {

	// We will pretend that the shop belong by an existing user.
    $userIdentifier = Utils::randomUserIdentifier($faker);

    return [
        'slug' => $faker->slug,
        'data' => [],
        'user_id' => $userIdentifier,
    ];
});
