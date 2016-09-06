<?php

use Sneefr\Models\User;

/**
 * Default factory.
 */
$factory->define(User::class, function ($faker) {
    return [
        'email' => $faker->optional(0.2)->safeEmail,
        'facebook_id' => $faker->numerify('#################'),
        'facebook_email' => $faker->optional(0.8)->safeEmail,
        'surname' => $faker->lastName,
        'given_name' => $faker->firstName,
        'gender' => $faker->randomElement(['male', 'female']),
        'locale' => null,
        'verified' => 1,
        'birthdate' => $faker->dateTimeBetween('-75 years', '-18 years'),
        'location' => null,
        'phone' => null,
        'lat' => null,
        'long' => null,
        'gamification_objectives' => null,
        'preferences' => '{"daily_digest":false}',
        // 'token' => $faker->regexify('[a-zA-Z0-9]{220,250}'),
        'token' => $faker->bothify(str_repeat('?#', 125)),
        'remember_token' => null,
        'created_at' => $faker->dateTimeBetween('1 year ago', '2 weeks ago'),
        'updated_at' => $faker->dateTimeBetween('2 weeks ago', '2 hours ago'),
        'deleted_at' => null
    ];
});

/**
 * Factory for a male person.
 */
$factory->defineAs(User::class, 'male', function ($faker) use ($factory) {
    return array_merge($factory->raw(User::class), [
        'gender' => 'male',
    ]);
});

/**
 * Factory for a female person.
 */
$factory->defineAs(User::class, 'female', function ($faker) use ($factory) {
    return array_merge($factory->raw(User::class), [
        'gender' => 'female',
    ]);
});
