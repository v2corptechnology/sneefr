<?php

return [
    'message' => [
        'inbox_title' => "You have a question about one item",
        'title'       => ":name just asked you a question about \":title\"",
        'content'     => ":message",
        'reply'       => ":name, is already the recipient of this email, just \"reply\" as usual. (or send an email to :email).",
    ],

    'purchased' => [
        'inbox_title'        => "You just bought something on Sneefr",
        'title'              => "You just bought \":item\" :price | You just bought :nb \":item\" :price",
        'content'            => "Don't forget to leave a review to <strong>:name</strong>, it will help other users know if this is a good seller.",
        'btn_evaluate'       => "Leave an evaluation",
        'btn_evaluate_title' => "Help the community by evaluating this seller",
    ],

    'sold' => [
        'inbox_title'        => "You just sold something on Sneefr",
        'title'              => "You just sold \":item\" :price | You just sold :nb \":item\" :price",
        'content'            => "Don't forget to leave a review to <strong>:name</strong>, it will help other users know if this is a good buyer.",
    ],
];
