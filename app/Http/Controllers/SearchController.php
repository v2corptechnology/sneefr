<?php

namespace Sneefr\Http\Controllers;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Session;
use Sneefr\Jobs\SaveSearch;
use Sneefr\Models\Ad;
use Sneefr\Models\Shop;

class SearchController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = $request->get('query');
        $type = $request->get('type', 'ad');
        //$sort = $request->get('sort');
        //$order = $request->get('order', 'desc');

        $ads = Ad::search($query)->paginate(16);
        $ads->setPath(route('search.index', ['type' => 'ad']));

        $shops = Shop::search($query)->paginate(16);
        $shops->setPath(route('search.index', ['type' => 'shop']));

        // Log the search action
        Queue::push(new SaveSearch($query, auth()->id(), $request));

        return view('search.index', compact('ads', 'shops', 'query', 'type', 'request'));
    }
}
