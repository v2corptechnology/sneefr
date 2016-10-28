<?php

return [
    'sender_email'         => "hello@sneefr.com",
    'sender_name'          => "Sidewalks",
    'signature'            => "The Sidewalks Team",
    'footer'               => "<a href=\":url\" title=\"To the site Sidewalks.com\">Sidewalks</a>, buy and sell to people you can trust.",
    'subscriptions'        => " | <a href=\":url\" title=\"Manage my email preferences\">Unsubscribe</a>",

    'activation-email' => [
        'title'        => "Sidewalks : activate your account",
        'lead'         => "Do you want to activate your account on Sidewalks?",
        'text'         => "It's easy, simply click on the huge button below to complete the procedure!",
        'button'       => "Active your account",
        'button_title' => "Commplete account activation procedure",
    ],

    'verify-email' => [
        'title' => "Sidewalks : Confirm your email address",
        'lead'         => "Do you want to update your email address on Sidewalks?",
        'text'         => "It's easy :name, simply click on the huge button below to complete the procedure!",
        'button'       => "Confirm this address",
        'button_title' => "Commplete email update procedure",
    ],

    'deal_refused_seller' => [
        'title'        => "Deal cancelled by the buyer",
        'lead'         => "It's a shame, :buyer no longer wants to buy your item :title",
        'text'         => "It happens sometimes, we're sorry. The good news is that :title is now back on the Sidewalks market.",
        'button_title' => "See the ad",
        'button'       => "See the ad",
    ],
];
