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
];
