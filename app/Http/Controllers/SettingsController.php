<?php namespace Sneefr\Http\Controllers;

class SettingsController extends Controller
{
    /**
     * Display settings of this user.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('me.show');
    }
}
