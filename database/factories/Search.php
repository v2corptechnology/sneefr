<?php

use Sneefr\Models\Search;
use Sneefr\Models\User;

/**
 * Default factory.
 */
$factory->define(Search::class, function ($faker) {
    return [
        'user_id' => factory(User::class)->create()->id,
        'body'    => $faker->sentence,
    ];
});
