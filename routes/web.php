<?php

/** Simple routes */

// Homepage
Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
// Display FAQ
Route::get('help', ['as' => 'help', function () {
    return view('pages.help');
}]);
// Terms of use
Route::get('terms', ['as' => 'terms', function () {
    return view('pages.terms');
}]);
// Privacy policy
Route::get('privacy', ['as' => 'privacy', function () {
    return view('pages.privacy');
}]);
// Pricing screen
Route::get('pricing', ['as' => 'pricing', function () {
    return view('pages.pricing');
}]);


/** Auth mechanisms */

Auth::routes();
Route::get('register/activation/{key}', ['as' => 'account_activation', 'uses' => 'AuthController@activate']);

// Disconnect the user
Route::get('logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
// Redirection to connection provider
Route::get('auth', ['as' => 'login', 'uses' => 'AuthController@login']);
// Handle callback from FB
Route::get('auth/callback', ['as' => 'auth.callback', 'uses' => 'AuthController@callback']);

/**
 * Routes related to person display
 */
// Profile, since Ads are the main entry point, redirect permanent to it
Route::get('profiles/{hash}', ['as' => 'profiles.show', function ($hash) {
    return redirect('/me', 301);
}]);
Route::delete('profile/{profile}', [
    'as'         => 'profiles.destroy',
    'uses'       => 'ProfilesController@destroy',
    'middleware' => 'auth',
]);
Route::get('profiles/{profile}/ads', [
    'as' => 'profiles.ads.index',
    function ($hash) {
        return redirect('/me', 301);
    },
]);
Route::get('profiles/{profile}/evaluations', [
    'as' => 'profiles.evaluations.index',
    function ($hash) {
        return redirect('/me', 301);
    },
]);
Route::get('profiles/{profile}/networks', [
    'as' => 'profiles.networks.index',
    function ($hash) {
        return redirect('/me', 301);
    },
]);
Route::get('profiles/{profile}/networks/referrals', [
    'as' => 'profiles.networks.referrals',
    function ($hash) {
        return redirect('/me', 301);
    },
]);
Route::get('profiles/{profile}/networks/followers', [
    'as' => 'profiles.networks.followers',
    function ($hash) {
        return redirect('/me', 301);
    },
]);
Route::get('profiles/{profile}/networks/followed', [
    'as' => 'profiles.networks.followed',
    function ($hash) {
        return redirect('/me', 301);
    },
]);
Route::get('profiles/{profile}/write', [
    'as' => 'profiles.write.create',
    function ($hash) {
        return redirect('/me', 301);
    },
]);
Route::get('profiles/{profile}/places', [
    'as' => 'profiles.places.index',
    function ($hash) {
        return redirect('/me', 301);
    },
]);
Route::get('profiles/{profile}/notifications', [
    'as' => 'profiles.notifications.index',
    function ($hash) {
        return redirect('/me', 301);
    },
]);
Route::get('profiles/{profile}/settings', [
    'as' => 'profiles.settings.edit',
    function ($hash) {
        return redirect('/me', 301);
    },
]);
Route::put('profiles/{profile}/settings', [
    'as'   => 'profiles.settings.update',
    'uses' => 'ProfilesController@updateSettings',
]);


Route::get('profiles/{profile}/parameters/confirmEmail/{key}', [
    'as'         => 'email_confirmation',
    'uses'       => 'ProfilesController@confirmEmail',
    'middleware' => ['auth'],
]);


/** Routes requiring to be authenticated */

Route::group(['middleware' => 'auth'], function ($router) {

    // Mark my messages in this discussion as read
    Route::put('discussions/{id}',
        ['as' => 'discussions.markRead', 'uses' => 'DiscussionsController@markRead']);
    // Choose an ad to sell
    Route::get('discussions/{id}/ads',
        ['as' => 'discussions.ads.index', 'uses' => 'DiscussionsController@chooseAd']);
    // Show a specific ad in a specific discussion
    Route::get('discussions/{id}/ads/{ad}',
        ['as' => 'discussions.ads.show', 'uses' => 'DiscussionsController@sell']);
    // Remove an ad from the discussion
    Route::delete('discussions/{id}/ads/{ad}',
        ['as' => 'discussions.ads.destroy', 'uses' => 'DiscussionsController@removeAd']);
    // Mark an ad as sold
    Route::patch('discussions/{id}/ads/{ad}',
        ['as' => 'discussions.ads.update', 'uses' => 'DiscussionsController@sold']);
    // Shop discussions
    Route::get('shopDiscussions/{shopSlug}',
        ['as' => 'shop_discussions.index', 'uses' => 'DiscussionsController@index']);
    // Show a specific discussion
    Route::get('shopDiscussions/{id}/{shopSlug}',
        ['as' => 'shop_discussions.show', 'uses' => 'DiscussionsController@show']);

    // Choose a buyer for this ad
    Route::get('ads/{ad}/chooseBuyer',
        ['as' => 'ads.chooseBuyer', 'uses' => 'AdController@chooseBuyer']);
    // Get an ad fragment
    Route::get('ads/{ad}/fragment',
        ['as' => 'ads.show.fragment', 'uses' => 'AdController@getAdFragment']);

    /** Out of resources scope */

    // Pusher auth for private channels
    Route::post('/pusherAuth', ['uses' => 'AuthController@pusherAuth']);
    Route::get('/pusherAuth', function () {
        return redirect()->route('welcome');
    });
    // User's settings
    Route::resource('me', 'SettingsController@show');

    /** Resources */
    // Ads
    Route::resource('ad', 'AdController', ['except' => ['show', 'index']]);
    // Ad images
    Route::resource('ads.images', 'ImagesController', ['only' => ['store', 'destroy']]);
    // Discussions
    Route::resource('discussions', 'DiscussionsController', ['only' => ['index', 'show']]);
    // Evaluations
    Route::resource('evaluations', 'EvaluationsController', ['only' => ['create', 'store']]);
    // Follows
    Route::resource('follows', 'FollowsController', ['only' => ['store', 'destroy']]);
    // Items
    Route::resource('items', 'ItemsController', ['only' => ['show', 'create', 'store', 'edit']]);
    // Likes
    Route::resource('likes', 'LikesController', ['only' => ['store']]);
    // Messages
    Route::resource('messages', 'MessagesController', ['only' => ['store']]);
    // Places
    Route::resource('places', 'PlacesController', ['only' => ['store']]);
    // Flag users or ads
    Route::resource('report', 'ReportController', ['only' => ['store']]);
    // Searches and shared searches
    Route::resource('search', 'SearchController', ['only' => ['store', 'destroy']]);
    // Shops
    Route::resource('shops', 'ShopsController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    // Subscriptions
    Route::resource('subscriptions', 'SubscriptionsController', ['only' => ['store']]);
    // Temporary images
    Route::resource('temporaryImages', 'TemporaryImagesController', ['only' => ['store', 'destroy']]);

    // Extra validation before making payments
    // Payments
    Route::resource('payments', 'PaymentsController', ['only' => ['create', 'store']]);
    Route::get('payments/connect', ['as' => 'payments.connect', 'uses' => 'PaymentsController@connect']);
    Route::get('payments/refuse/{ad}', ['as' => 'payments.refuse', 'uses' => 'PaymentsController@refuse']);
});

/** Publicly accessible resources that must be after auth or resource declaration */
// Ad sharing
Route::get('share/ad/{ad}', ['as' => 'ads.share', 'uses' => 'SharesController@shareAd']);
// Ad display,
Route::get('ad/{ad}', ['as' => 'ad.show', 'uses' => 'AdController@show', 'middleware' => 'shared']);
// Search results
Route::get('search', ['as' => 'search.index', 'uses' => 'SearchController@index']);
// Shop display
Route::get('shops/{shops}/search', ['as' => 'shops.search', 'uses' => 'ShopsController@search']);
Route::get('shops/{shops}/evaluations', ['as' => 'shops.evaluations', 'uses' => 'ShopsController@evaluations']);
Route::resource('shops', 'ShopsController', ['only' => ['show']]);
// Place display
Route::resource('places', 'PlacesController', ['only' => ['show']]);
Route::get('places/{places}/followers', ['as' => 'places.followers', 'uses' => 'PlacesController@followers']);
Route::get('places/{places}/nearby', ['as' => 'places.nearby', 'uses' => 'PlacesController@nearbyAds']);
Route::get('places/{places}/search', ['as' => 'places.search', 'uses' => 'PlacesController@search']);
Route::get('places/{places}/searchAround', ['as' => 'places.searchAround', 'uses' => 'PlacesController@searchAround']);


/** Admins only */
Route::group(['middleware' => ['auth', 'team.admin']], function ($router) {
    Route::get('admin/users', ['as' => 'admin.users', 'uses' => 'AdminController@users']);
    Route::get('admin/ads', ['as' => 'admin.ads', 'uses' => 'AdminController@ads']);
    Route::get('admin/deals', ['as' => 'admin.deals', 'uses' => 'AdminController@deals']);
    Route::get('admin/reported', ['as' => 'admin.reported', 'uses' => 'AdminController@reported']);
    Route::get('admin/misc', ['as' => 'admin.misc', 'uses' => 'AdminController@misc']);
    Route::get('admin/searches', ['as' => 'admin.searches', 'uses' => 'AdminController@searches']);
    Route::get('kitchensink', 'KitchensinkController@index');
});

/** Devs only */
Route::group(['middleware' => ['auth', 'team.developer']], function ($router) {
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});


/**
 * Route bindings
 */
Route::bind('item', function ($value) {
    return \Sneefr\Models\Ad::findOrFail(explode('-', $value)[0]);
});
Route::bind('shop', function ($value) {
    return \Sneefr\Models\Shop::where('slug', $value)->withTrashed()->first();
});
Route::bind('place', function ($value) {
    return \Sneefr\Models\Place::where('slug', $value)->first();
});
Route::bind('profile', function ($value, $route) {
    $hashids = app('Hashids\Hashids');

    $decoded = $hashids->decode($value);

    if (! isset($decoded[0])) {
        abort(404);
    }

    return $decoded[0];
});
Route::bind('ad', function ($value, $route) {
    return explode('-', $value)[0];
});

/**
 * Route patterns
 */
// Match latitude and longitude values that fall within the correct range. See
// http://stackoverflow.com/a/18690202
Route::pattern('places', '^@[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$');
// Shop's slug
Route::pattern('shopSlug', '[a-zA-Z-]+');
Route::pattern('shop', '[a-zA-Z-]+');
