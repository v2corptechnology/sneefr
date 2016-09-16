<?php

return [
    '403' => [
        'page_title' => "Forbidden",
        'header'     => "Forbidden",
        'lead'       => "Move along, there's nothing to see!",
        'text'       => "You must be trying to see a page you don't have access to. We could almost think you're a hacker. Almost.",
        'help'       => "If you think you shouldn't be banned from seeing this page, <a href=\":helpUrl\" title=\"We're here to help\">contact us</a> ;-)",
    ],
    '404' => [
        'page_title' => "Page not found",
        'header'     => "404, this is a four ow four!",
        'lead'       => "You found a resource that doesn't exist anymore, we apologize.",
        'text'       => "We advise you to <a href=\":searchUrl\" title=\"Look for the item of your dreams\">check the latest ads</a> or get yourself a cup of coffee.",
        'help'       => "If this is your 50th cup, <a href=\":helpUrl\" title=\"We're here to help\">contact us</a> ;-)",
    ],
    '410' => [
        'page_title' => "This ad or user doesn't exist anymore",
        'header'     => ":'(",
        'lead'       => "This ad or user is gone",
        'text'       => "Sorry for that!<br>We will delete this page soon, meanwhile <a href=\":searchUrl\" title=\"Search through the new comers\">search for your next sneefer</a> or get yourself a cup of coffee.",
        'help'       => "If this is your 50th cup, <a href=\":helpUrl\" title=\"We're here to help\">contact us</a> ;-)",
    ],
    '504' => [
        'page_title' => "Timeout",
        'header'     => "Wow, we exceeded waiting time with a 504",
        'lead'       => "That means we apologize and we invite you to try again.",
        'text'       => "Now or a little bit later ;)",
        'help'       => "If the problem persists <a href=\":helpUrl\" title=\"We're here to help\">contact us</a> ;-)",
    ],
    'missing_scopes' => [
        'page_title' =>"We need more information",
        'heading' =>"sneefR needs just a little more information",
        'explanation' =>"Why do we need this information?",
        'email' => "<strong>Your email</strong> : to be able to contact you when a sale is about to be made! You can manage your notification preferences from your sneefR profile.",
        'birthdate' => "<strong>Your birth date</strong> : It allows us to protect minors. Don't worry, your age will never be public!",
        'friendlist' => "<strong>Your friendlist</strong> : sneefR allows you to find in a flash the ads published by your friends and they will see yours so you can sell faster! You can always unfollow someone from your sneefR profile.",
        'privacy' => "We only use your data to give you the best experience possible on sneefR. We will <strong>never</strong> sell your data.",
        'button' => "Try again",
        'button_title' => "Re-login",
    ],
];
