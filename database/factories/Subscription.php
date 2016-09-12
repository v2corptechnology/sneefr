<?php

use Sneefr\Models\User;

/**
 * Default factory.
 */
$factory->define(\Laravel\Cashier\Subscription::class, function ($faker) {
    return [
        'user_id'     => factory(User::class)->create()->id,
        'name'        => 'shop',
        'stripe_id'   => $faker->lexify('sub_??????????????'),
        'stripe_plan' => 'yearly',
        'quantity'    => 1,
    ];
});
