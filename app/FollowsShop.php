<?php namespace Sneefr;

use Illuminate\Http\Request;
use Sneefr\Models\Shop;

/**
 * Class FollowsShop
 *
 * @package \Sneefr\Http\Controllers
 */
class FollowsShop
{
    /**
     * @var \Sneefr\Models\Shop
     */
    protected $shop;

    /**
     * Add a follow to a shop.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function follow(Request $request)
    {
        $this->shop = Shop::withTrashed()->where('slug', $request->get('item'))->first();

        if (is_null($this->shop)) {
            abort(404, 'This shop does not exists');
        }

        $this->shop->followers()->attach(auth()->id());
    }

    /**
     * Remove a follow to a shop.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function unfollow(Request $request)
    {
        $this->shop = Shop::withTrashed()->where('slug', $request->get('item'))->first();

        if (is_null($this->shop)) {
            abort(404, 'This shop does not exists');
        }

        // Remove the follow
        $this->shop->followers()->detach(auth()->id());
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectStore()
    {
        return redirect()->route('shops.show', $this->shop)
            ->with('success', 'You are now following this shop.');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectDestroy()
    {
        return redirect()->route('shops.show', $this->shop)
            ->with('success', 'You are not following this shop anymore.');
    }
}
