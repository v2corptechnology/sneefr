<?php

use FactoryUtilities as Utils;
use Sneefr\Models\Search;

/**
 * Default factory.
 */
$factory->define(Search::class, function ($faker) {

    // We will pretend that the search has been done by an existing user.
    $userIdentifier = Utils::randomUserIdentifier($faker);

    return [
        'user_id' => $userIdentifier,
        'body' => $faker->sentence,
    ];
});
