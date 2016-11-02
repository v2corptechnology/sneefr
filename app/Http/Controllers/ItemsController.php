<?php

namespace Sneefr\Http\Controllers;

use Sneefr\Events\AdWasPosted;
use Sneefr\Events\ItemWasViewed;
use Sneefr\Http\Requests\CreateAdRequest;
use Sneefr\Models\Ad;
use Sneefr\Models\Stock;
use Sneefr\Models\Tag;

class ItemsController extends Controller
{
    public function show(Ad $ad)
    {
        $ad->load('shop.evaluations');

        // Verify this ad is viewable
        // Quickfix : a disconnected user cannot see an ad
        //$this->authorize($ad);

        event(new ItemWasViewed($ad, auth()->user()));

        return view('items.show', compact('ad'));
    }

    /**
     * Display the form to create a new item.
     */
    public function create()
    {
        if (auth()->user()->cannot('create', Ad::class)) {
            return redirect()->route('shops.show', auth()->user()->shop)
                ->with('stripe_modal', true);
        }

        $tags = Tag::all()->pluck('title', 'id');

        return view('items.create', compact('tags'));
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

        $ad->tags()->sync($request->input('tags'));

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

        $tags = Tag::all()->pluck('title', 'id');

        return view('ad.edit', compact('ad', 'tags'));
    }
}
