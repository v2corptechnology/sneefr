<?php

return [
    'header'  => [
        'not_verified'           => "Careful, this profile is not verified",
        'geolocation_fill'       => "Fill in my location",
        'geolocation_fill_title' => "Fill in my location to see the closest ads",
        'geolocation_missing'    => "We don't know where this user is sneefing from :(",
    ],
    'sidebar' => [
        'ads'                     => "{0} :name is not selling anything |[0,1] :nb ad |[2,Inf] :nb ads",
        'ads_title'               => "{0} :name is not selling anything |[0,1] :name has one ad |[2,Inf] :name has :nb ads",
        'sold'                    => "{0} No sale yet |[0,1] :nb item sold |[2,Inf] :nb items sold",
        'evaluations'             => "[-1,0] No review |[0,Inf] :ratio% of positive reviews",
        'evaluations_title'       => "[-1,0] :name has no review yet|[0,Inf] :name has :ratio% positive reviews",
        'level'                   => ":name is at the level :rank",
        'search_fulfill'          => "I've got it",
        'search_fulfill_title'    => "Publish an ad for this item",
        'report'                  => "Report :name's behavior",
        'report_title'            => "Warn the team about a bad behavior of this user",
        'shop'                    => "{0} Nothing to sell yet |[0,1] :nb ad to sell |[2,Inf] :nb ads to sell",
        'me'                      => [
            'parameters'          => "My settings",
            'parameters_title'    => "Manage my account settings",
            'parameters_details'  => "Manage <a href=\":urlEmail\" title=\"Edit my e-mail\">my e-mail address</a>, <a href=\":urlNotifications\" title=\"Activate/deactivate notifications\">my notifications</a>,…",
        ],
    ],

    'ads' => [
        'page_title'    => ":name's ads",
        'head'          => "{0} :name isn't selling anything |[0,1] :name is selling :nb item |[2,Inf] :name is selling :nb items",
        'head_filtered' => "{0} No results |[0,1] :nb result |[2,Inf] :nb results",
        'filtering'     => "You see ads filtered with <strong>:filter</strong>, <a href=\":url\" title=\"Remove the filter\">clear the filter</a>.",
        'empty_text'    => ":name is not selling anything",
        'filtering'     => "You see ads filtered with <strong>:filter</strong>, <a href=\":url\" title=\"Remove the filter\">clear the filter</a>.",
        'no_results'    => "No ads with <strong>:filter</strong>, <a href=\":url\" title=\"Remove the filter\">clear the filter</a>.",
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

    'places' => [
        'page_title'                                 => ":name's places of interest",
        'head'                                       => ":name's places of interest",
        'empty_text'                                 => ":name has no place of interest",
        'add_place_of_interest_label'                => "Follow activity in a new place of interest",
        'add_place_of_interest_disabled_placeholder' => "Activate javascript to use this field",
        'add_place_of_interest_placeholder'          => "High school, college, office, district...",
        'button_save_place_of_interest'              => "Add",
    ],

    'parameters' => [
        'page_title'                => "My settings",
        'your_info'                 => "Your information",
        'given_name_label'          => "First name",
        'surname_name_label'        => "Last name",
        'email_label'               => "Your email address",
        'email_placeholder'         => "Ex: hello@sneefr.com",
        'email_not_yet_validated'   => "You have not confirmed your email <strong>:email</strong> yet",
        'email_text'                => "(it is confidential and will never be seen by other users)",
        'location_label'            => "Location",
        'location_placeholder'      => "Ex: Santa Monica, CA 90401",
        'location_waiting'          => "Be patient, sneefR is geolocating you...",
        'location_error'            => "Are you a ninja ? Impossible to geolocate you.",
        'location_timeout_warning'  => "Make sure you accept the location request in the <a href=\"https://www.google.fr/search?q=comment+activer+la+localisation\">zone of your browser</a>",
        'button_save_parameters'    => "Save",
        'your_notifications'        => "Notification settings",
        'daily_digest_label'        => "Get a daily alert if I have unread notifications",
        'button_save_notifications' => "Save",
        'button_danger_zone'        => "Delete my account",
        // Phone panel
        'phone_title'               => 'Phone verification',
        'phone_verified_label'      => 'Phone number verified',
        'phone_label'               => 'Verify your phone number',
        'retry_msg'                 => 'If you do not receive SMS, try again',
        'retry'                     => 'retry',
        'sms_code'                  => 'Enter code here',
        'sms_msg'                   => 'Your verification code for sneefR is::code',
    ],

    'settings' => [
        'payment' => [
            'heading'        => "Get paid with SneefPay",
            'explain'        => "SneefPay uses Stripe to ensure secure payment.",
            'be_careful'     => "When accepting payment with SneefPay, remember that:
                            <br>You will need to create and maintain a Stripe account if you don't have one already.
                            <br>You will be fully responsible for chargeback and transaction issues handling.
                            <br>Stripe charges sellers a 2,9% + $30 cts payment processing fee on every transaction.
                            <br><strong>sneefR doesn't charge any fee at all!</strong>
                            <br>Pro sellers: Link your business Stripe account to receive payments from your customers.",
            //is it possible to put the last sentence in italic
            'linked'         => "Your Stripe account is linked. You can now request secure payments on sneefR.",
            'btn_link'       => "Stripe Connect",
            'btn_link_title' => "You will be redirected to Stripe's website",
        ],
        'avatar_title'              => 'Profile picture',
        'avatar_upload_button'      => 'Upload Image',
    ],
];
