<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Config for sneefr website
    |--------------------------------------------------------------------------
    */
    'LOAD_DEBUGBAR' => env('LOAD_DEBUGBAR'),
    'APP_ENV'       => env('APP_ENV'),

    'NOTIFY_UNREAD_NOTIFICATION_AFTER' => 60 * 24, // Minutes
    'NOTIFY_UNREAD_MESSAGE_AFTER'      => 15, // Minutes
    'NOTIFY_UNREAD_MESSAGE_BEFORE'     => 25, // Minutes
    'NOTIFY_AD_LOCK_AFTER'             => 60 * 8, // Minutes after lock to let pass before the reminder
    'EXPIRE_AD_LOCK_AFTER'             => 60 * 24, // Minutes

    /*
    |--------------------------------------------------------------------------
    | Keys used across different services
    |--------------------------------------------------------------------------
    */
    'keys' => [
        'APP_HASH_KEY'           => env('APP_HASH_KEY'),
        // Google services
        'GOOGLE_API_KEY'         => env('GOOGLE_API_KEY'),
        // Pusher services
        'PUSHER_KEY'             => env('PUSHER_KEY'),
        'PUSHER_CLUSTER'         => env('PUSHER_CLUSTER'),
        // Facebook services
        'FACEBOOK_CLIENT_ID'     => env('FACEBOOK_CLIENT_ID'),
        'FACEBOOK_CLIENT_SECRET' => env('FACEBOOK_CLIENT_SECRET'),
        // MapBox services
        'MAPBOX_KEY'             => env('MAPBOX_KEY'),
        // Cloudimage services
        'CLOUD_IMAGE_ROOT_URL'   => env('CLOUD_IMAGE_ROOT_URL'),
        // Intercom chat service
        'INTERCOM_APP_ID'        => env('INTERCOM_APP_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Automatically followed team members
    |--------------------------------------------------------------------------
    |
    | Those identifiers from the users table are automatically added as
    | connection with "forced.follow_activity" origin when a newly registered
    | user has no connections
    |
    */

    'auto_follow_user_ids' => [
        2, // Arthur
        3, // Selma
        4, // Jeremy
    ],

    /*
    |--------------------------------------------------------------------------
    | Staff user ids
    |--------------------------------------------------------------------------
    |
    | These are the staff identifiers used on the users table. We filter results
    | from the staff mostly in the stats.
    |
    */

    'staff_user_ids'             => [
        1, // Romain
        2, // Arthur
        3, // Selma
        4, // Jeremy
    ],
    /*
    |--------------------------------------------------------------------------
    | Staff facebook ids
    |--------------------------------------------------------------------------
    |
    | These are the staff facebook identifiers mostly used to display special
    | menus, access stats or debug tools.
    |
    */

    'staff_facebook_ids'         => [
        // Those who can see the stats
        'administrators' => [
            603204913117901, // Selma
            10152391232875356, // Jeremy
            10152937772934901, // Arthur
            10152914548827090, // Romain
        ],
        // Those who have the `team` badge and are added by default for each new user
        'team'           => [
            603204913117901, // Selma
            10152391232875356, // Jeremy
            10152937772934901, // Arthur
            10152914548827090, // Romain
        ],
        // Those who can access the logs
        'developers'     => [
            10152914548827090,  // Romain
            1211978335509141,   // Bilel
        ]
    ],

];
