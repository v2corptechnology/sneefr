<?php

use Sneefr\Models\Shop;
use Sneefr\Models\User;

/**
 * Default factory.
 */
$factory->define(Shop::class, function ($faker) {

    $slug = $faker->slug;

    return [
        'slug'    => $slug,
        'user_id' => factory(User::class)->create()->id,
        'data'    => [
            'slug'             => $slug,
            'name'             => $faker->company,
            'description'      => $faker->catchPhrase,
            'logo'             => $faker->randomNumber . ".jpeg",
            'cover'            => $faker->randomNumber . ".jpeg",
            'terms'            => 1,
            'location'         => $faker->address,
            'latitude'         => $faker->latitude,
            'longitude'        => $faker->longitude,
            'font_color'       => $faker->hexcolor,
            'background_color' => $faker->hexcolor,
        ],
    ];
});
