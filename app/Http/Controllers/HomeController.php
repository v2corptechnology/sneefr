<?php

namespace Sneefr\Http\Controllers;

use Illuminate\Http\Request;
use Sneefr\Http\Requests;
use Sneefr\Models\Ad;
use Sneefr\Models\Shop;
use Sneefr\Models\Tag;

class HomeController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $selectedTag = $request->get('tag');

        $tags = Tag::withCount('shops')->orderBy('shops_count', 'desc')->take(12)->get();

        $shopsInTag = Tag::when($selectedTag, function ($query) use ($selectedTag) {
            return $query->where('alias', $selectedTag);
        })->with('shops')->get()->pluck('shops')->flatten()->shuffle()->take(6);


        $topShops = cache()->rememberForever('highlighted_shops', function () {
            return Shop::highlighted()->take(4)->get();
        })->load('evaluations');

        return view('pages.home', compact('tags', 'topShops', 'bestSellers', 'shopsInTag'));
    }
}
