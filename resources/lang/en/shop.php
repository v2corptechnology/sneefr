<?php

return [
    'follow'      => "Follow",
    'unfollow'    => "Unfollow",
    'ads'         => [
        'page_title'                => ":name",
        'empty_text'                => ":name doesn't sell anything (yet).",
        'empty_text_for_owner'      => ":name looks promising but has nothing to sell. Create your first ad.",
        'btn_create_first_ad'       => "create your first ad",
        'btn_create_first_ad_title' => "It's fast and easy",
    ],
    'create'      => [
        'page_title'              => "Open your shop in 2 minutes",
        'heading'                 => "Open my shop",
        'tagline'                 => "It's so easy!",
        'name_label'              => "Name of your shop",
        'name_placeholder'        => "Phile's Fabulous Shop",
        'slug_label'              => "Personalized URL of your shop",
        'slug_prepend'            => "https://",
        'slug_append'             => ".sidewalks.city",
        'slug_placeholder'        => "phile-s-fabulous-shop",
        'description_label'       => "Description of your shop",
        'description_placeholder' => "Hours, location, policies, additional details",
        'location_label'          => "Location",
        'logo_label'              => "Your photo or your logo",
        'cover_label'             => "Photo of your beautiful shop",
        'terms_label'             => "I’ve read and agree to the <a href=\":link\" title=\"Read the terms of use\" target=\"_blank\">terms of use</a>",
        'save_label'              => "Open my shop",
        'edit_label'              => "Save changes",
        'category_label'          => "The categories of products in your shop",
    ],
    'sidebar'     => [
        'evaluations'       => "[-1,0] No review |[0,Inf] :ratio% of positive reviews",
        'evaluations_title' => "[-1,0] :name has no review yet|[0,Inf] :name has :ratio% positive reviews",
    ],
    'evaluations' => [
        'page_title'     => "Reviews of :name",
        'head'           => "{0} :name has no review yet |[0,1] :name's review |[2,Inf] :name's :nb reviews",
        'empty_text'     => ":name has no review yet",
        'positive_title' => "Positive review",
        'negative_title' => "Negative review",
        'profile_title'  => "See :name's profile",
        'forced_text'    => "This review was automatically sent because the buyer did not respond to our evaluation request.",
    ],
];
