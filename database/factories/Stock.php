<?php

use Sneefr\Models\Ad;
use Sneefr\Models\Stock;

/**
 * Default factory.
 */
$factory->define(Stock::class, function ($faker) {

    $initialQuantity = $faker->numberBetween(2, 100);

    return [
        'ad_id'     => factory(Ad::class)->create()->id,
        'initial'   => $initialQuantity,
        'remaining' => $faker->numberBetween(1, $initialQuantity),
    ];
});
