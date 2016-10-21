<?php

return [
    'header'  => [
        'not_verified'           => "Careful, this profile is not verified",
        'geolocation_fill'       => "Fill in my location",
        'geolocation_fill_title' => "Fill in my location to see the closest ads",
        'geolocation_missing'    => "We don't know where this user is sneefing from :(",
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
