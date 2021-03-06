<?php

namespace Sneefr\Http\Controllers;

use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Http\Request;
use Laravel\Cashier\Subscription;
use Sneefr\Http\Requests\StoreShopRequest;
use Sneefr\Jobs\UpdateShopColors;
use Sneefr\Models\Ad;
use Sneefr\Models\Shop;
use Sneefr\Models\Tag;
use Sneefr\Services\Image;

/**
 * Handle actions related to shop management.
 */
class ShopsController extends Controller
{
    /**
     * The disk instance used for images.
     *
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $disk;

    /**
     * Create a new instance of the controller.
     *
     * @param  \Illuminate\Contracts\Filesystem\Factory  $filesystemFactory
     *
     * @return self
     */
    public function __construct(FilesystemFactory $filesystemFactory)
    {
        $this->disk = $filesystemFactory->disk('images');
    }

    /**
     * Displays the ads of the shop.
     *
     * @param \Sneefr\Models\Shop $shop
     *
     * @return \Illuminate\View\View
     */
    public function show(Shop $shop)
    {
        // Quickfix : a disconnected user cannot see a shop
        //$this->authorize($shop);

        $shop->load('ads', 'evaluations');

        $displayedAds = $shop->ads;

        return view('shops.show', compact('shop', 'displayedAds'));
    }

    /**
     * Search ads in this shop.
     *
     * @param \Sneefr\Models\Shop      $shop
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function search(Shop $shop, Request $request)
    {
        $this->authorize($shop);

        if (! $request->has('q')) {
            return redirect()->route('shops.show', $shop);
        }

        $shop->load('ads', 'evaluations');

        $q = $request->get('q');

        $displayedAds = Ad::where('shop_id', $shop->getId())->latest()->get()->filter(function ($ad) use ($q) {
            return stripos($ad->title, $q) !== false;
        });

        return view('shops.show', compact('shop', 'displayedAds', 'q'));
    }

    /**
     * Display the form to create a new shop.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Check if the user has no shop
        if (auth()->user()->hasShop()) {
            return redirect()->route('home')->with('error', 'You can own only one shop');
        }

        $tags = Tag::all()->pluck('title', 'id');

        return view('shops.create', compact('tags'));
    }

    /**
     * Store a new shop entry in the database.
     *
     * @param \Sneefr\Http\Requests\StoreShopRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(StoreShopRequest $request)
    {
        // Check if the user has no shop
        if (auth()->user()->hasShop()) {
            return redirect()->route('home')->with('error', 'You can own only one shop');
        }

        // Store data
        $shop = new Shop([
            'slug'    => $request->input('slug'),
            'user_id' => auth()->id(),
            'data'    => $request->except('_token', '_method', 'cover', 'logo'),
        ]);

        // Store the images
        $images = $this->moveImages($shop, $request);

        // Default colors
        $colors = [
            'background_color' => '#FFFFFF',
            'font_color'       => '#000000',
        ];

        // Update shop data with real images names
        $shop->data = array_merge($request->all(), $images, $colors);

        // Save new shop
        $shop->save();

        $shop->tags()->sync($request->input('tags'));

        // Update shop's colors later on
        $this->dispatch(new UpdateShopColors($shop));

        // Disable the shop if no subscription is running
        if (!auth()->user()->subscribed('main')) {
            $shop->delete();
        }

        return redirect()->route('shops.show', $shop);
    }

    /**
     * Display edit form for a shop.
     *
     * @param \Sneefr\Models\Shop $shop
     *
     * @return \Illuminate\View\View
     */
    public function edit(Shop $shop)
    {
        $this->authorize($shop);

        $tags = Tag::all()->pluck('title', 'id');

        return view('shops.edit', compact('shop', 'tags'));
    }

    /**
     * Persist changes made on the shop.
     *
     * @param \Sneefr\Models\Shop             $shop
     * @param \Sneefr\Http\Requests\StoreShopRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Shop $shop, StoreShopRequest $request)
    {
        $this->authorize($shop);

        $shop->tags()->sync($request->input('tags'));

        $images = [];
        // Update the images only if necessary
        if ($this->imagesHasBeenChanged($request)) {
            $images = $this->moveImages($shop, $request);
        }

        // Start by using the existing data then overwrite it
        // with any new piece of data that may come from the request.
        $updatedData = collect($shop->data)
            ->merge($request->except('_token', '_method', 'slug', 'terms'))
            ->merge($images);

        // Persist the changes
        $shop->update(['data' => $updatedData]);

        // Run the job only when needed
        if ($this->imagesHasBeenChanged($request)) {
            // Update shop's colors later on
            $this->dispatch(new UpdateShopColors($shop));
        }

        return redirect()->route('shops.show', $shop);
    }

    /**
     * Completely erase shop info.
     *
     * @param \Sneefr\Models\Shop $shop
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Shop $shop)
    {
        if(! auth()->check() && ! App::environment('local', 'staging')) {
            abort(403);
        }

        Subscription::where('user_id', auth()->id())->forceDelete();
        Ad::where('shop_id', $shop->getId())->withTrashed()->update(['shop_id' => null]);
        auth()->user()->update(['payment' => null, 'stripe_id' => null, 'card_brand' => null, 'card_last_four' => null, 'trial_ends_at' => null]);

        $shop->forceDelete();

        \Artisan::call('algolia:clear');

        return redirect()->route('home');
    }

    /**
     * Display evaluations of a shop.
     *
     * @param \Sneefr\Models\Shop $shop
     *
     * @return \Illuminate\View\View
     */
    public function evaluations(Shop $shop)
    {
        $this->authorize($shop);

        $shop->load('ads', 'evaluations');

        return view('shops.evaluations', compact('shop'));
    }

    /**
     * Check the request has at least one image.
     *
     * @param \Sneefr\Http\Requests\StoreShopRequest $request
     *
     * @return bool
     */
    private function imagesHasBeenChanged(StoreShopRequest $request) : bool
    {
        return $request->hasFile('cover') || $request->hasFile('logo');
    }

    /**
     * Move uploaded images to shop's folder.
     * TODO: refactor this to be flexible and less bound to the caller. Job/Event ?
     *
     * @param \Sneefr\Models\Shop      $shop
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    private function moveImages(Shop $shop, Request $request) : array
    {
        $disk = $this->disk;
        $imageService = app(Image::class);

        return collect($request->allFiles())->map(function ($file, $key) use ($disk, $shop, $imageService) {
            $slug = $shop->getRouteKey();
            $oldName = $shop->{"get" .ucfirst($key). "Name"}();
            $name = $imageService::generateHash($file);

            // Remove previous image
            if ($oldName) {
                $disk->delete("shops/{$slug}/{$oldName}");
            }

            // Save the new one
            $disk->put("shops/{$slug}/{$name}", file_get_contents($file));

            return $name;
        })->toArray();
    }
}
