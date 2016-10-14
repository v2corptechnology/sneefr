<?php

namespace Sneefr\Http\Controllers;

use Illuminate\Http\Request;
use Sneefr\Http\Requests\CreatePlaceRequest;
use Sneefr\Models\Place;
use Sneefr\Models\PlaceName;
use Sneefr\Repositories\Ad\AdRepository;

class PlacesController extends Controller
{
    /**
     * Displays the ads from the followers of this place.
     *
     * @param \Sneefr\Models\Place $place
     *
     * @return \Illuminate\View\View
     */
    public function show(Place $place)
    {
        $place->load('followers', 'followers.ads');

        // TODO: use a proper relation to fetch only ads outside of shop
        $displayedAds = $followersAds = $place->followers->pluck('ads')->flatten()->where('shop_id', null);

        $nearbyAds = $place->nearbyAds()->get();

        return view('places.show', compact('place', 'displayedAds', 'nearbyAds', 'followersAds'));
    }

    /**
     * Displays the followers of this place.
     *
     * @param \Sneefr\Models\Place $place
     *
     * @return \Illuminate\View\View
     */
    public function followers(Place $place)
    {
        $place->load('followers', 'followers.ads');

        // TODO: use a proper relation to fetch only ads outside of shop
        $followersAds = $place->followers->pluck('ads')->flatten()->where('shop_id', null);

        $nearbyAds = $place->nearbyAds()->get();

        return view('places.followers', compact('place', 'nearbyAds', 'followersAds'));
    }

    /**
     * Displays the ads around this place.
     *
     * @param \Sneefr\Models\Place $place
     *
     * @return \Illuminate\View\View
     */
    public function nearbyAds(Place $place)
    {
        $place->load('followers', 'followers.ads');

        // TODO: use a proper relation to fetch only ads outside of shop
        $followersAds = $place->followers->pluck('ads')->flatten()->where('shop_id', null);

        $displayedAds = $nearbyAds = $place->nearbyAds()->get();

        return view('places.show', compact('place', 'nearbyAds', 'followersAds', 'displayedAds'));
    }

    /**
     * Search ads in the followers of this place.
     *
     * @param \Sneefr\Models\Place                 $place
     * @param \Illuminate\Http\Request             $request
     * @param \Sneefr\Repositories\Ad\AdRepository $adRepository
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function search(Place $place, Request $request, AdRepository $adRepository)
    {
        if (! $request->has('q')) {
            return redirect()->route('places.show', $place);
        }

        $place->load('followers', 'followers.ads');

        // TODO: use a proper relation to fetch only ads outside of shop
        $followersAds = $place->followers->pluck('ads')->flatten()->where('shop_id', null);

        $nearbyAds = $place->nearbyAds()->get();

        $q = $request->get('q');

        $displayedAds = $adRepository->of($place->followers->pluck('id'), $q);

        return view('places.show', compact('place', 'nearbyAds', 'followersAds', 'displayedAds', 'q'));
    }

    /**
     * Search ads around this place.
     *
     * @param \Sneefr\Models\Place                 $place
     * @param \Illuminate\Http\Request             $request
     * @param \Sneefr\Repositories\Ad\AdRepository $adRepository
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function searchAround(Place $place, Request $request, AdRepository $adRepository)
    {
        if (! $request->has('q')) {
            return redirect()->route('places.show', $place);
        }

        $place->load('followers', 'followers.ads');

        // TODO: use a proper relation to fetch only ads outside of shop
        $followersAds = $place->followers->pluck('ads')->flatten()->where('shop_id', null);

        $nearbyAds = $place->nearbyAds()->get();

        $q = $request->get('q');

        $displayedAds = $place->nearbyAds($q)->get();

        return view('places.show', compact('place', 'nearbyAds', 'followersAds', 'displayedAds', 'q'));
    }

    /**
     * Create a new place.
     *
     * @param \Sneefr\Http\Requests\CreatePlaceRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreatePlaceRequest $request)
    {
        $place = Place::firstOrNew(['service_place_id' => $request->input('place_id')]);

        $placeInfo = $this->getPlaceInfo($request->input('place_id'));

        // Create new place only if not exists
        if (! $place->exists) {
            $place->latitude = $placeInfo->geometry->location->lat;
            $place->longitude = $placeInfo->geometry->location->lng;
            $place->slug = '@' . $place->latitude . ',' . $place->longitude;
            $place->save();
        }

        // Fetch the existing place name for the current language
        $placeName = PlaceName::firstOrNew(['place_id' => $place->getId(), 'language' => config('app.locale')]);

        // Add a new translation only when  necessary
        if (! $placeName->exists) {
            $placeName->name = $placeInfo->name;
            $placeName->formatted_address = $placeInfo->formatted_address;
            $placeName->address_components = $placeInfo->address_components;
            $placeName->save();
        }

        // Since the user is the creator, automatically follow it
        $place->followers()->attach(auth()->id());

        return redirect()->route('places.show', $place)
            ->with('success', trans('feedback.profile_location_adding_success'));
    }

    /**
     * Retrieve place info from API call.
     * TODO: move it in a service or something else
     *
     * @param string $placeId
     *
     * @return \stdClass
     */
    protected function getPlaceInfo(string $placeId) : \stdClass
    {
        $client = new \GuzzleHttp\Client();

        //&language=fr
        // Prepare the API endpoint to call
        $endpoint = sprintf(
            'https://maps.googleapis.com/maps/api/place/details/json?placeid=%s&key=%s&language=%s',
            $placeId,
            config('sneefr.keys.GOOGLE_API_KEY'),
            config('app.locale')
        );

        // Make the request
        $result = $client->request('GET', $endpoint);

        if (! $result->getStatusCode() == 200) {
            \Log::alert('Impossible to fetch place info', $endpoint);
            dd("An error occured");
        }

        return json_decode($result->getBody()->__toString())->result;
    }
}
