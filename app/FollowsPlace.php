<?php namespace Sneefr;

use Illuminate\Http\Request;
use Sneefr\Repositories\Place\PlaceRepository;

/**
 * Class FollowsPlace
 *
 * @package \Sneefr
 */
class FollowsPlace
{
    /**
     * @var \Sneefr\Models\Place
     */
    protected $place;
    /**
     * Add a follow to a place.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function follow(Request $request)
    {
        $this->place = $this->findPlace($request->get('item'));

        $followerIds = $this->place->followers->pluck('id')->all();

        if (! in_array(auth()->id(), $followerIds)) {
            // Add the user to the followers
            $this->place->followers()->attach(auth()->id());
        }
    }

    /**
     * Remove a follow to a place.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function unfollow(Request $request)
    {
        $this->place = $this->findPlace($request->get('item'));

        // Add the user to the followers
        $this->place->followers()->detach(auth()->id());
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectStore()
    {
        return redirect()->route('places.show', $this->place)
            ->with('success', 'You are now following this place.');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectDestroy()
    {
        return redirect()->route('places.show', $this->place)
            ->with('success', 'You are not following this place anymore.');
    }

    protected function findPlace($value)
    {
        // Remove @ and separate latitude and longitude
        list($latitude, $longitude) = explode(',', substr($value, 1));

        return \Sneefr\Models\Place::where('latitude', $latitude)->where('longitude', $longitude)->firstOrFail();
    }
}
