<?php

namespace Sneefr\Http\Controllers;

use Illuminate\Http\Request;
use Sneefr\Models\Shop;

class HighlightedShopsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shops = cache()->rememberForever('highlighted_shops', function () {
            return Shop::highlighted()->with('evaluations')->take(6)->get();
        });

        return view('admin.highlightedShops.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shop = Shop::findOrFail($request->input('shop_id'));

        $shops = cache()->get('highlighted_shops');

        cache()->forget('highlighted_shops');

        $shops->push($shop);

        cache()->rememberForever('highlighted_shops', function () use ($shops) {
            return $shops->values();
        });

        return redirect()->route('highlightedShops.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $shops = cache()->get('highlighted_shops', Shop::highlighted()->with('evaluations')->take(6)->get());

        cache()->forget('highlighted_shops');

        $order = $shops->pluck('id')->toArray();

        $currentIndex = array_search($id, $order);

        $targetIndex = $currentIndex + $request->input('direction');

        $this->moveElement($order, $currentIndex, $targetIndex);

        $shops = $shops->sortBy(function ($shop) use ($order) {
            return array_search($shop->id, $order);
        })->values();

        cache()->rememberForever('highlighted_shops', function () use ($shops) {
            return $shops;
        });

        return redirect()->route('highlightedShops.index');
    }

    public function moveElement(&$array, $a, $b)
    {
        $out = array_splice($array, $a, 1);
        array_splice($array, $b, 0, $out);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $shop = Shop::findOrFail($id);

        $shops = cache()->get('highlighted_shops')->reject(function ($value, $key) use ($shop) {
            return $value->id == $shop->id;
        });

        cache()->forget('highlighted_shops');

        cache()->rememberForever('highlighted_shops', function () use ($shops) {
            return $shops;
        });

        return redirect()->route('highlightedShops.index');
    }
}
