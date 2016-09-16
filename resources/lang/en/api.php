<?php

return [

    // Feed item resources.
    // Each sub-key match a type of feed item.

    'feed_item' => [

        'ad' => [
            'header' => "Added ‘:title’",
            'content' => "Do you have one to sell?",
        ],
        'deal' => [
            'header_i_bought' => "You’ve bought ‘:title’ from :name",
            'header_bought' => ":buyer bought something from :seller",
            'header_i_sold' => "You’ve sold ‘:title’ to :name",
            'header_sold' => ":seller sold something to :buyer",
        ],
        'discussion' => [
            'header' => ":personA and :personB are talking about an ad",
        ],
        'like' => [
            'header' => "Likes ‘:title’ of :name",
        ],
        'like_search' => [
            'header' => ":personA likes the following search ‘:search’ from :personB",
        ],
        'search' => [
            'header' => "Search ‘:search’",
            'content' => "Do you have one to sell?",
        ],
        'follow' => [
            'header' => "Now follows :name",
        ],
        'follow_place' => [
            'header' => "",
        ],
    ],

];
