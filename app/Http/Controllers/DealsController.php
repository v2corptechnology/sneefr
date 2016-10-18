<?php

namespace Sneefr\Http\Controllers;

class DealsController extends Controller
{
    /**
     * Display the latest deals of current user.
     */
    public function index()
    {
        $deals = auth()->user()->recentDeals;

        return view('deals.index', compact('deals'));
    }
}
