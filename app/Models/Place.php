<?php namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;
use Sneefr\Models\Traits\Likeable;
use Sneefr\Services\SearchService;

class Place extends Model
{
    use Likeable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'places';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes we can mass assign.
     *
     * @var array
     */
    protected $fillable = ['latitude', 'longitude', 'service_place_id'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
        'data'      => 'array',
    ];

    /**
     * Get the collection of likes related to this record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Relationship to the names of this place.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function names()
    {
        return $this->hasMany(PlaceName::class);
    }

    /**
     * Get the ads nearby this place.
     *
     * @param string $filter (optional) The text we filter the ads with
     *
     * @return mixed
     */
    public function nearbyAds($filter = null)
    {
        $param = [
            'q'    => $filter,
            'sort' => 'coordinates',
            'lat'  => $this->getLatitude(),
            'long' => $this->getLongitude(),
        ];
        $search = app(SearchService::class);

        return $search->for('ad')->with($param);;
    }

    public function getRouteKey() : string
    {
        return $this->slug;
    }

    /**
     * Get the identifier of the place.
     *
     * @return int
     */
    public function getId() : int
    {
        return (int) $this->id;
    }

    /**
     * Get the latitude of the place.
     *
     * @return float
     */
    public function getLatitude() : float
    {
        return (float) $this->latitude;
    }

    /**
     * Get the longitude of the place.
     *
     * @return float
     */
    public function getLongitude() : float
    {
        return (float) $this->longitude;
    }

    /**
     * Get the name of this place.
     *
     * @return string
     */
    public function getName() : string
    {
        $currentLocaleName = $this->names->where('language', config('app.locale'))->first();
        $englishLocaleName = $this->names->where('language', 'en')->first();

        if (! $currentLocaleName) {
            if (! $englishLocaleName) {
                return $this->names->first()->getName();
            }

            return $englishLocaleName->getName();
        }

        return $currentLocaleName->getName();
    }

    /**
     * Get the long name of this place.
     *
     * @return string
     */
    public function getLongName() : string
    {
        $currentLocaleName = $this->names->where('language', config('app.locale'))->first();
        $englishLocaleName = $this->names->where('language', 'en')->first();

        if (! $currentLocaleName) {
            if (! $englishLocaleName) {
                return $this->names->first()->getLongName();
            }

            return $englishLocaleName->getLongName();
        }

        return $currentLocaleName->getLongName();
    }

    /**
     * Get the map URL of this place.
     *
     * @param int  $width
     * @param int  $height
     * @param bool $retina
     * @param int  $zoom
     *
     * @return string
     */
    public function getMapUrl(int $width = 400, int $height = 400, $retina = false, $zoom = 12)
    {
        $retina = $retina ? '@2x' : '';

        return vsprintf(
            "//api.mapbox.com/v4/mapbox.emerald/pin-m-%s+FE7569(%F,%F)/%F,%F,%u/%ux%u%s.jpg?access_token=%s", [
                strtolower($this->getName()[0]),
                $this->getLongitude(),
                $this->getLatitude(),
                $this->getLongitude(),
                $this->getLatitude(),
                $zoom,
                $width,
                $height,
                $retina,
                config('sneefr.keys.MAPBOX_KEY')
        ]);
    }
}
