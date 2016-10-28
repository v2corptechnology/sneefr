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
Route::get('auth', ['as' => 'login', 'uses' => 'AuthController@redirectToProvider']);
// Handle callback from FB
Route::get('auth/callback', ['as' => 'auth.callback', 'uses' => 'AuthController@callback']);

/**
 * Routes related to person display
 */

Route::delete('profile/{profile}', [
    'as'         => 'profiles.destroy',
    'uses'       => 'ProfilesController@destroy',
    'middleware' => 'auth',
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

    /** Out of resources scope */
    // User's settings
    Route::get('me', ['as' => 'me.show', 'uses' =>'SettingsController@show']);
    // Messages
    Route::post('messages/{item}', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);

    /** Resources */
    // Ads
    Route::resource('ad', 'AdController', ['except' => ['show', 'index']]);
    // Ad images
    Route::resource('ads.images', 'ImagesController', ['only' => ['store', 'destroy']]);
    // Deals history
    Route::resource('deals', 'DealsController', ['only' => ['index']]);
    // Evaluations
    Route::resource('evaluations', 'EvaluationsController', ['only' => ['create', 'store']]);
    // Items
    Route::resource('items', 'ItemsController', ['except' => ['index', 'show', 'update']]);
    // Flag users or ads
    Route::resource('report', 'ReportController', ['only' => ['store']]);
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
// Items
Route::resource('items', 'ItemsController', ['only' => ['show']]);
// Search results
Route::get('search', ['as' => 'search.index', 'uses' => 'SearchController@index']);
// Shop display
Route::get('shops/{shop}/search', ['as' => 'shops.search', 'uses' => 'ShopsController@search']);
Route::get('shops/{shop}/evaluations', ['as' => 'shops.evaluations', 'uses' => 'ShopsController@evaluations']);
Route::resource('shops', 'ShopsController', ['only' => ['show']]);


/** Admins only */
Route::group(['middleware' => ['auth', 'team.admin']], function ($router) {
    Route::get('admin/tools', ['as' => 'admin.tools', 'uses' => 'AdminController@tools']);
    Route::put('admin/tools/{id}', ['as' => 'admin.tools.update', 'uses' => 'AdminController@toolsUpdate']);
    Route::get('admin/users', ['as' => 'admin.users', 'uses' => 'AdminController@users']);
    Route::get('admin/ads', ['as' => 'admin.ads', 'uses' => 'AdminController@ads']);
    Route::get('admin/deals', ['as' => 'admin.deals', 'uses' => 'AdminController@deals']);
    Route::get('admin/reported', ['as' => 'admin.reported', 'uses' => 'AdminController@reported']);
    Route::get('admin/searches', ['as' => 'admin.searches', 'uses' => 'AdminController@searches']);
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
