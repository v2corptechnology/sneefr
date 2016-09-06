<?php

use FactoryUtilities as Utils;
use Sneefr\Models\Ad;
use Sneefr\Models\User;

/**
 * Default factory.
 */
$factory->define(Ad::class, function ($faker) {

    // We will pretend that the ad has been published by an existing user.
    $userIdentifier = Utils::randomUserIdentifier($faker);
    // We will pretend that the ad attached to an existing category.
    $catgoryIdentifier = Utils::randomCategoryIdentifier($faker);

    return [
        'user_id' => $userIdentifier,
        'category_id' => $catgoryIdentifier,
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'amount' => $faker->randomNumber(3),
        'currency' => 'EUR',
        'location' => $faker->postcode.' '.$faker->city,
        'lat' => $faker->latitude,
        'long' => $faker->longitude,
        'images' => '["0.jpg"]',
        'condition_id' => $faker->numberBetween(1, 5),
    ];
});

/**
 * Factory for a sold ad.
 *
 * It assigns the sale to one of the existing users.
 * It throws an exception if none exists.
 */
$factory->defineAs(Ad::class, 'sold', function ($faker) use ($factory) {

    // We will assign the sale to one of the existing users.
    $userIdentifier = Utils::randomUserIdentifier(
        $faker,
        'Factory needs at least one existing User to create a sold Ad'
    );

    return array_merge($factory->raw(Ad::class), [
        'sold_to' => $userIdentifier,
    ]);
});
