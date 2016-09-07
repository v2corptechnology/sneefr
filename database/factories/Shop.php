<?php

use Sneefr\Models\Shop;
use Sneefr\Models\User;

/**
 * Default factory.
 */
$factory->define(Shop::class, function ($faker) {
    return [
        'slug'    => $faker->slug,
        'data'    => [],
        'user_id' => factory(User::class)->create()->id,
    ];
});
