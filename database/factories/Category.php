<?php

use Sneefr\Models\Category;

/**
 * Default factory.
 */
$factory->define(Category::class, function ($faker) {
    return [
        'name'     => $faker->name,
        'child_of' => null,
    ];
});
