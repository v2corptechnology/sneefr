<?php

namespace Sneefr\Http\Controllers;

use Illuminate\Http\Request;

use Sneefr\Http\Requests;
use Sneefr\Models\Ad;
use Sneefr\Models\Category;
use Sneefr\Models\Shop;

class HomeController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $categories = Category::parent()->with('childrens')->get();
        $categories->child = null;
        $categories->parent = null;
        $category = Category::find($request->get('category'));

        if($category) {
            $categories->child = $category->id;
            $categories->parent = $category->child_of ?: $category->id;
        }

        if($category) {
            $shopsByCategory = Category::whereIn('id', $category->getChildsIds())->with('shops')->get()->take(6)->pluck('shops')->collapse()->unique('shop');
        }else {
            $shopsByCategory = Shop::with('evaluations')->take(6)->get();
        }

        $bestSellers = Ad::take(6)->get();
        $topShops = Shop::withCount('ads')->with('evaluations')->orderBy('ads_count', 'desc')->take(4)->get();

        return view('pages.home', compact('shopsByCategory', 'topShops', 'bestSellers', 'categories'));
    }
}
