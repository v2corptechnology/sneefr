<?php

return [

    /**
     * If a jobs fail we will send you a notification via these channels.
     * You can use "mail", "slack" or both.
     */
    'senders' => ['mail'],

    'mail'  => [
        'from' => 'hello@sneefr.com',
        'to'   => 'romain.sauvaire@gmail.com',
    ],

    /**
     * If want to send notifications to slack you must
     * install the "maknz/slack" package
    'slack' => [
        'channel'  => '#failed-jobs',
        'username' => 'Failed Job Bot',
        'icon'     => ':robot_face:',
    ],
     */
];
