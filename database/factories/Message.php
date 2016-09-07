<?php

use Sneefr\Models\Discussion;
use Sneefr\Models\Message;
use Sneefr\Models\User;

/**
 * Default factory.
 */
$factory->define(Message::class, function ($faker) {
    return [
        'discussion_id' => factory(Discussion::class)->create()->id,
        'from_user_id'  => factory(User::class)->create()->id,
        'to_user_id'    => factory(User::class)->create()->id,
        'body'          => $faker->paragraph,
    ];
});

/**
 * Factory for a read message.
 */
$factory->defineAs(Message::class, 'read', function ($faker) use ($factory) {

    $message = $factory->raw(Message::class);

    return array_merge($message, ['read_at' => $faker->dateTime('yesterday')]);
});
