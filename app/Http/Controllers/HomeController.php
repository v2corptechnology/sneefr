<?php

namespace Sneefr\Http\Controllers;

use Sneefr\Models\Shop;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $topShops = cache()->rememberForever('highlighted_shops', function () {
            return Shop::highlighted()->take(6)->get();
        })->load('evaluations');

        return view('pages.home', compact('topShops'));
    }
}
