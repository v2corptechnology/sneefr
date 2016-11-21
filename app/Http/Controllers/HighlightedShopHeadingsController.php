<?php

namespace Sneefr\Http\Controllers;

use Illuminate\Http\Request;

class HighlightedShopHeadingsController extends Controller
{
    public function update($index, Request $request)
    {
        $headings = cache()->get('highlighted_shops_headings', collect());

        $headings->put($index, [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        cache()->forget('highlighted_shops_headings');

        cache()->rememberForever('highlighted_shops_headings', function () use ($headings) {
            return $headings;
        });

        return redirect()->route('highlightedShops.index');
    }
}
