<?php

use Sneefr\Models\User;

/**
 * Default factory.
 */
$factory->define(User::class, function ($faker) {
    return [
        'avatar'                  => "pig.jpg",
        'email'                   => $faker->safeEmail,
        'password'                => $faker->password,
        'facebook_id'             => $faker->numerify('#################'),
        'facebook_email'          => $faker->optional(0.8)->safeEmail,
        'surname'                 => $faker->lastName,
        'given_name'              => $faker->firstName,
        'gender'                  => $faker->randomElement(['male', 'female']),
        'locale'                  => null,
        'verified'                => 1,
        'birthdate'               => $faker->dateTimeBetween('-75 years', '-18 years'),
        'location'                => null,
        'phone'                   => null,
        'lat'                     => null,
        'long'                    => null,
        'preferences'             => '{"daily_digest":false}',
        // 'token' => $faker->regexify('[a-zA-Z0-9]{220,250}'),
        'token'                   => $faker->bothify(str_repeat('?#', 125)),
        'remember_token'          => null,
    ];
});

/**
 * Factory for a user with a payment card
 */
$factory->defineAs(User::class, 'with-payment', function ($faker) use ($factory) {
    $user = $factory->raw(User::class);

    return array_merge($user, [
        'stripe_id'      => $faker->lexify('cus_??????????????'),
        'card_brand'     => "visa",
        'card_last_four' => "4242",
        'payment'        => '{"scope": "read_write", "livemode": true}',
    ]);
});
