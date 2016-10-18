<?php

use Sneefr\Models\Ad;
use Sneefr\Models\User;

/**
 * Default factory.
 */
$factory->define(\Sneefr\Models\Transaction::class, function ($faker) {

    $amount = $faker->randomNumber(6);

    return [
        'ad_id'     => factory(Ad::class)->create()->id,
        'buyer_id'  => factory(User::class)->create()->id,
        'seller_id' => factory(User::class)->create()->id,
        'stripe_data' => ['pop'],
        'details'   => [
            'delivery' => [
                'method'       => 'c2c',
                'shop_address' => 'pick address',
                'fee'          => null,
            ],
            'details'  => [
                'quantity'   => $faker->randomDigitNotNull,
                'extra_info' => $faker->sentence,
            ],
            'charge'   => [
                'amount'   => $amount,
                'currency' => 'usd',
                'price'    => $amount/100 . '$',
                'data'     => $faker->words($nb = 10),
            ],
        ],
    ];
});
