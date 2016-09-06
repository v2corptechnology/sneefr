<?php

use FactoryUtilities as Utils;
use Sneefr\Models\Discussion;
use Sneefr\Models\Message;

/**
 * Default factory.
 */
$factory->define(Discussion::class, function ($faker) {
    return [];
});

/**
 * Factory with some messages.
 */
$factory->defineAs(Discussion::class, 'withMessages', function ($faker) use ($factory) {

    // We will pretend that the discussion is involving two existing users.
    $fromUserId = Utils::randomUserIdentifier($faker);
    $toUserId = Utils::randomUserIdentifier($faker);

    // Dedupe IDs in case they are identical.
    if ($fromUserId === $toUserId) {
        $toUserId += 1;
    }

    // Create the discussion
    $discussion = factory(Discussion::class)->create();

    // Save the participants to the discussion
    $discussion->participants()->attach([$fromUserId, $toUserId]);

    // Create the messages
    $message1 = $factory->create(Message::class, [
        'from_user_id' => $fromUserId,
        'to_user_id' => $toUserId,
        'body' => 'This is the first message'
    ]);
    $message2 = $factory->create(Message::class, [
        'from_user_id' => $toUserId,
        'to_user_id' => $fromUserId,
        'body' => 'This is the second message'
    ]);
    $message3 = $factory->create(Message::class, [
        'from_user_id' => $fromUserId,
        'to_user_id' => $toUserId,
        'body' => 'This is the third message'
    ]);

    return $discussion;
});
