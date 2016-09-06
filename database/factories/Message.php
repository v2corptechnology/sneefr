<?php

use FactoryUtilities as Utils;
use Sneefr\Models\Message;

/**
 * Default factory.
 */
$factory->define(Message::class, function ($faker) {

    // We will pretend that the discussion is involving two existing users.
    $fromUserId = Utils::randomUserIdentifier($faker);
    $toUserId = Utils::randomUserIdentifier($faker);

    // Dedupe IDs in case they are identical.
    if ($fromUserId === $toUserId) {
        $toUserId += 1;
    }

    return [
        'discussion_id' => 1,
        'from_user_id' => $fromUserId,
        'to_user_id' => $toUserId,
        'body' => $faker->paragraph,
    ];
});

/**
 * Factory for a read message.
 */
$factory->defineAs(Message::class, 'read', function ($faker) use ($factory) {

    $user = $factory->raw(Message::class);

    return array_merge($user, ['read_at' => $faker->dateTime('yesterday')]);
});
