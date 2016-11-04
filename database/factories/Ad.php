<?php

use Sneefr\Models\Ad;
use Sneefr\Models\Category;
use Sneefr\Models\User;
use Sneefr\Models\Shop;

/**
 * Default factory.
 */
$factory->define(Ad::class, function ($faker) {

    $initialQuantity = $faker->numberBetween(2, 100);

    return [
        'user_id'            => factory(User::class)->create()->id,
        'shop_id'            => factory(Shop::class)->create()->id,
        'remaining_quantity' => $faker->numberBetween(1, $initialQuantity),
        'category_id'        => factory(Category::class)->create()->id,
        'title'              => $faker->sentence,
        'description'        => $faker->paragraph,
        'amount'             => $faker->randomNumber(3),
        'currency'           => 'USD',
        'location'           => $faker->postcode . ' ' . $faker->city,
        'latitude'           => $faker->latitude,
        'longitude'          => $faker->longitude,
        'images'             => '["0.jpg"]',
    ];
});

/**
 * Factory for a sold ad.
 * It assigns the sale to one of the existing users.
 * It throws an exception if none exists.
 */
$factory->defineAs(Ad::class, 'sold', function ($faker) use ($factory) {
    return array_merge($factory->raw(Ad::class), [
        'sold_to' => factory(User::class)->create()->id,
    ]);
});
