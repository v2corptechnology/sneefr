<?php namespace Sneefr\Http\Controllers;

use Illuminate\Http\Request;
use Sneefr\Services\ActivityFeed\ActivityFeed;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get the activity feed.
        $actions = ActivityFeed::of(auth()->id())->page(1);

        // List of the person this user is following
        $followedUsers = auth()->user()->following()->users();

        // Get the list of locations followed by the person.
        $followedPlaces = auth()->user()->following()->places();

        // Hook for views telling if the person has just logged in.
        $hasLoggedIn = $request->exists('first_time');

        return view('dashboard.index',
            compact('actions', 'hasLoggedIn', 'followedUsers', 'followedPlaces')
        );
    }
}
