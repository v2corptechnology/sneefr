<?php

return [
    'sender_email'         => "hello@sneefr.com",
    'sender_name'          => "sneefR",
    'signature'            => "The sneefR Team",
    'footer'               => "<a href=\":url\" title=\"To the site sneefR.com\">sneefR</a>, buy and sell to people you can trust.",
    'subscriptions'        => " | <a href=\":url\" title=\"Manage my email preferences\">Unsubscribe</a>",

    'waiting-notification' => [
        'title'        => ":nb unread notification|:nb unread notifications",
        'lead'         => ":name, you have <strong>one unread notification</strong>.| :name, you have <strong>:nb unread notifications</strong>.",
        'text'         => "We send you this reminder to make sure you don't miss a sale, the answer of a seller or a notification.",
        'button'       => "See my notifications",
        'button_title' => "See my notifications on sneefR",
    ],

    'activation-email' => [
        'title'        => "sneefR : activate your account",
        'lead'         => "Do you want to activate your account on sneefR?",
        'text'         => "It's easy, simply click on the huge button below to complete the procedure!",
        'button'       => "Active your account",
        'button_title' => "Commplete account activation procedure",
    ],

    'verify-email' => [
        'title' => "sneefR : Confirm your email address",
        'lead'         => "Do you want to update your email address on sneefR?",
        'text'         => "It's easy :name, simply click on the huge button below to complete the procedure!",
        'button'       => "Confirm this address",
        'button_title' => "Commplete email update procedure",
    ],

    'expired_lock_seller' => [
        'title'        => "Unlocked ad : :title",
        'lead'         => "The buyer didn't confirm buying this item",
        'text'         => "This item was saved for the buyer for 24h, this period has expired.",
        'button_title' => "See the ad",
        'button'       => "See the ad",
    ],

    'expired_lock_buyer' => [
        'title'        => "Purchase missed : :title",
        'lead'         => "This item is no longer saved for you",
        'text'         => "This item was saved for you for 24h, this period has expired.",
        'button_title' => "See the ad",
        'button'       => "See the ad",
    ],

    'expiring_lock' => [
        'title'        => "You're going to miss :title",
        'lead'         => "You only have 8 hours left to confirm the purchase of this item",
        'text'         => "This item is saved for you for 24h, this period expires soon.",
        'button_title' => "See the ad",
        'button'       => "See the ad",
    ],

    'deal_finished_seller' => [
        'title'        => "Congratulations",
        'lead'         => "You've just sold :title for :finalPrice to :buyer",
        'text'         => "If you asked for a secure payment, this e-mail means that :buyer has paid :finalPrice and is waiting to receive the item. Please check your balance directly on your Stripe dashboard. If you did not ask for a secure payment, you must see directly with :buyer how to seal the deal. In both cases, and in order to help the sneefR community, please be kind and leave a review to :buyer",
        'info'         => "extra info :info",
        'address'      => ":address",
        'button_title' => "Leave a review",
        'button'       => "Leave a review",
    ],

    'deal_finished_buyer' => [
        'title'        => "Congratulations",
        'lead'         => "You've jut bought :title for :finalPrice to :seller!",
        'text'         => "If you paid with your credit card directly on sneefR, this email means that :seller received the payment. We invite you to get in touch directly with :seller regarding shipping details. If you haven't paid yet, you must see directly with :seller how to seal the deal. In both cases, and in order to help the sneefR community, please be kind and leave a review to :seller",
        'button_title' => "Leave a review",
        'button'       => "Leave a review",
    ],

    'deal_refused_seller' => [
        'title'        => "Deal cancelled by the buyer",
        'lead'         => "It's a shame, :buyer no longer wants to buy your item :title",
        'text'         => "It happens sometimes, we're sorry. The good news is that :title is now back on the sneefR market.",
        'button_title' => "See the ad",
        'button'       => "See the ad",
    ],
];
