const elixir = require('laravel-elixir');

require('laravel-elixir-vue');

// Here is where JavaScript dependencies are stored.
var path_to_modules = '../../../node_modules/';

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {
    mix.sass('app.scss');
    mix.sass(['pages/login.scss'], 'public/css/sneefr.login.css');
    mix.sass(['pages/pricing.scss'], 'public/css/sneefr.pricing.css');
    // Styles coming from JavaScript components.
    mix.styles([
        path_to_modules + 'dropzone/dist/min/dropzone.min.css',
        path_to_modules + 'ideal-image-slider/ideal-image-slider.css',
        path_to_modules + 'ideal-image-slider/themes/default/default.css',

        // Main CSS of the application.
        '../../../public/css/app.css'

    ], 'public/css/all.css');

    mix.scripts([

        // Scripts coming from JavaScript components.
        path_to_modules+'jquery/dist/jquery.min.js',
        path_to_modules+'pusher-js/dist/pusher.min.js',
        path_to_modules+'bootstrap-sass/assets/javascripts/bootstrap.js',
        path_to_modules+'geocomplete/jquery.geocomplete.min.js',
        path_to_modules+'clipboard/dist/clipboard.min.js',
        path_to_modules+'timeago/jquery.timeago.js',

        // Main script of the application.
        'app.js',
        'sneefr.pushes.js',

    ], 'public/js/all.js');

    // Dashboard
    mix.scripts([
            path_to_modules+'ideal-image-slider/ideal-image-slider.min.js',
            'sneefr.dashboard.js'],
        'public/js/sneefr.dashboard.js'
    );

    // Validation on-the-fly of forms
    mix.scripts([
            path_to_modules+'jquery-validation/dist/jquery.validate.js',
            'sneefr.autovalidate.js'],
        'public/js/sneefr.autovalidate.js'
    );

    // Ad display
    mix.scripts([
            path_to_modules+'baguettebox.js/dist/baguetteBox.min.js',
            path_to_modules+'javascript-flex-images/flex-images.min.js',
            'sneefr.ad.js',
        ],
        'public/js/sneefr.ad.js'
    );

    // Ad creation and edition
    mix.scripts([
            path_to_modules+'dropzone/dist/min/dropzone.min.js',
            'sneefr.ad_edition.js',
        ],
        'public/js/sneefr.ad_edition.js'
    );

    // Billing
    mix.scripts(['sneefr.billing.js'], 'public/js/sneefr.billing.js');

    // Delivery
    mix.scripts(['sneefr.delivery.js'], 'public/js/sneefr.delivery.js');

    // Shops
    mix.scripts(['sneefr.shops.js'], 'public/js/sneefr.shops.js');

    // Like
    mix.scripts(['sneefr.like.js'], 'public/js/sneefr.like.js');

    // Auto-navigate with select dropdown
    mix.scripts(['sneefr.auto-navigate.js'], 'public/js/sneefr.auto-navigate.js');

    // Messages
    mix.scripts(['sneefr.messages.js'], 'public/js/sneefr.messages.js');

    // Auto Complete
    mix.scripts(['sneefr.autocomplete.js'], 'public/js/sneefr.autocomplete.js');

    // Cache buster
    mix.version([
        'css/all.css',
        'css/sneefr.login.css',
        'css/sneefr.pricing.css',
        'js/all.js',
        'js/sneefr.dashboard.js',
        'js/sneefr.autovalidate.js',
        'js/sneefr.ad.js',
        'js/sneefr.ad_edition.js',
        'js/sneefr.billing.js',
        'js/sneefr.delivery.js',
        'js/sneefr.shops.js',
        'js/sneefr.like.js',
        'js/sneefr.auto-navigate.js',
        'js/sneefr.messages.js',
        'js/sneefr.autocomplete.js'
    ]);
});
