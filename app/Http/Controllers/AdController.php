<?php

namespace Sneefr\Http\Controllers;

use Sneefr\Http\Requests\StoreItemRequest;
use Sneefr\Models\Ad;
use Sneefr\Models\Shares;

class AdController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $ad = Ad::withTrashed()->find($id);

        return redirect()->route('items.show', $ad->getSlug(), 301);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int                                  $id
     * @param \Sneefr\Http\Requests\StoreItemRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id, StoreItemRequest $request)
    {
        // Get the ad to edit
        $ad = Ad::findOrFail($id);

        // Check the rights for this user to edit this ad
        $this->authorize('update', $ad);

        // Update the data
        $ad->update($request->except(['images']));

        $ad->tags()->sync($request->input('tags'));

        return redirect()->route('ad.show', $ad)
            ->with('success', trans('feedback.ad_edit_success'));
    }
}
