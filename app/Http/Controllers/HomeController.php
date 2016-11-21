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
        $highlights = cache()->rememberForever('highlighted_shops', function () {
            return Shop::highlighted()->get();
        })->load('evaluations')->take(config('sneefr.home_featured_items'));

        return view('pages.home', compact('highlights'));
    }
}
