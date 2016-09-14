<?php

namespace Sneefr\Http\Controllers;

use Sneefr\Http\Requests\CreateAdRequest;
use Sneefr\Models\Ad;
use Sneefr\Models\Category;
use Sneefr\Models\Stock;

class ItemsController extends Controller
{
    /**
     * Display the form to create a new item.
     */
    public function create()
    {
        $categories = Category::getTree();

        return view('items.create', compact('categories'));
    }

    /**
     * Store the new item.
     *
     * @param \Sneefr\Http\Requests\CreateAdRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateAdRequest $request)
    {
        // Store the ad
        $ad = Ad::create($request->all());

        // Set the stock of this ad
        $ad->stock()->save(new Stock([
            'initial'   => $request->input('quantity'),
            'remaining' => $request->input('quantity'),
        ]));

        // Notify the ad has been created
        event(new AdWasPosted($ad));

        // Redirect when auto-sharing was checked
        if ($request->input('auto_share')) {
            return redirect()->route('ads.share', $ad);
        }

        return redirect()->route('ad.show', $ad);
    }
}
