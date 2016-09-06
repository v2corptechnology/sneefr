<?php

return [
    'no_results' => "No ads with <strong>:filter</strong>, <a href=\":url\" title=\"Remove the filter\">clear the filter</a>.",
    'sidebar'    => [
        'ads'             => "{0} No item sold by followers |[0,1] :nb item sold by followers |[2,Inf] :nb items sold by followers",
        'ads_title'       => "Ads published by followers",
        'ads_text'        => "Make a deal with a follower!",
        'followers'       => "{0} No follower |[0,1] :nb follower |[2,Inf] :nb followers",
        'followers_title' => "People following this place",
        'followers_text'  => "{0} Nobody is following this place |[1,5] This place is starting to attract followers |[5,10] This place is getting attractive |[10,Inf] This place is popular!",
        'nearby'          => "{0} No ad |[1,2] :nb ad |[2,Inf] :nb ads",
        'nearby_title'    => "{0} Looks like there are no ads near :name |[1,2] One ad near :name |[2,Inf] :nb ads near :name",
        'nearby_text'     => "Ads near this place",
        'follow'          => "Follow",
        'follow_title'    => "Display ads from this place in my feed",
        'unfollow'        => "Unfollow",
        'unfollow_title'  => "Unfollow this place",
    ],
    'ads'        => [
        'page_title'       => "Ads published by the followers of :name",
        'empty_text'       => "Followers of :name haven't published any ad yet",
        'alternative_text' => "Nothing is sold by the followers of this place. Have a look at the <a href=\":url\" title=\"View the ads nearby\">ads nearby the place</a>",
    ],
    'networks'   => [
        'page_title'           => "Followers of :name",
        'head'                 => ":name is followed by",
        'following_empty_text' => "Nobody follows :name :(",
        'profile_title'        => "View :name's profile",
    ],
    'nearby'     => [
        'page_title'       => "Ads at :name",
        'head'             => "{0} Nothing to buy around :name |[0,1] :nb ad to buy nearby :name |[2,Inf] :nb ads to buy nearby :name",
        'empty_text'       => "Looks like there is currently no ad near :name",
        'alternative_text' => "Nothing for sale around this place. Have a look at the <a href=\":url\" title=\"View the ads\">ads published by the followers</a>",
    ],
];
