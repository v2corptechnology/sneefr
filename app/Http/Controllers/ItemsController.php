<?php

namespace Sneefr\Http\Controllers;

use Sneefr\Events\AdWasPosted;
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
        if (auth()->user()->cannot('create', Ad::class)) {
            return redirect()->route('shops.show', auth()->user()->shop)
                ->with('stripe_modal', true);
        }

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

        // Notify the ad has been created
        event(new AdWasPosted($ad));

        // Redirect when auto-sharing was checked
        if ($request->input('auto_share')) {
            return redirect()->route('ads.share', $ad);
        }

        return redirect()->route('ad.show', $ad);
    }

    /**
     * Edit an item.
     *
     * @param \Sneefr\Models\Ad $ad
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Ad $ad)
    {
        // Check the rights for this user to edit this ad
        $this->authorize('update', $ad);

        $categories = Category::getTree();

        $shops = \Auth::user()->shops;

        return view('ad.edit', compact('ad', 'categories', 'shops'));
    }
}
