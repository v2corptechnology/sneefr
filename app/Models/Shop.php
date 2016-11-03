<?php

namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\Traits\LogsActivity;

class Shop extends Model
{
    use LogsActivity, Searchable, SoftDeletes;

    /**
     * The attributes we can mass assign.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'slug', 'data'];

    /**
     * The attributes that needs to be logged by the LogsActivity trait.
     *
     * @var array
     */
    protected static $logAttributes = ['data'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = ['data' => 'array'];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $record = $this->toArray();
        $record = array_merge($record, $record['data']);
        $record['_geoloc'] = [
            'lat' => $this->getLatitude(),
            'lng' => $this->getLongitude(),
        ];

        unset($record['data'], $record['latitude'], $record['longitude']);

        return $record;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship from the shop to its ads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ads()
    {
        return $this->hasMany(Ad::class, 'shop_id')->latest();
    }

    /**
     * Tags applied to this shop.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Relationship to the evaluations of this shop.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class)->valid();
    }

    public function scopeHighlighted($query)
    {
        return $query->withCount('ads')->orderBy('ads_count', 'desc');
    }

    /**
     * Get the value of the model's route key.
     *
     * @return string
     */
    public function getRouteKey() : string
    {
        return $this->slug;
    }

    /**
     * Get all the data stored for this shop.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getData(string $key = null)
    {
        if (is_null($key)) {
            return $this->data;
        }

        if (! isset($this->data[$key])) {
            return null;
        }

        return $this->data[$key];
    }

    /**
     * Get the identifier of this shop.
     *
     * @return int
     */
    public function getId () : int
    {
        return $this->id;
    }

    /**
     * Get the name of the shop.
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->getData('name');
    }

    /**
     * Get the description filled for the shop.
     *
     * @return string
     */
    public function getDescription() : string
    {
        return (string) $this->getData('description');
    }

    /**
     * Get the location's name of the shop.
     *
     * @return string
     */
    public function getLocation() : string
    {
        return $this->getData('location');
    }

    /**
     * Get the latitude's name of the shop.
     *
     * @return float
     */
    public function getLatitude() : float
    {
        return (float) $this->getData('latitude');
    }

    /**
     * Get the longitude's name of the shop.
     *
     * @return float
     */
    public function getLongitude() : float
    {
        return (float) $this->getData('longitude');
    }

    /**
     * Get the url for the cover, given the dimensions.
     *
     * @param mixed $dimensions
     *
     * @return string
     */
    public function getCover($dimensions = '1400x450') : string
    {
        return \Img::cover($this, $dimensions);
    }

    /**
     * Get the name of the picture used for cover.
     *
     * @return string
     */
    public function getCoverName() : string
    {
        return (string) $this->getData('cover');
    }

    /**
     * Get the url for the logo, given the dimensions.
     *
     * @param mixed $dimensions
     *
     * @return string
     */
    public function getLogo($dimensions = '80x80') : string
    {
        return \Img::logo($this, $dimensions);
    }

    /**
     * Get the name of the logo.
     *
     * @return string
     */
    public function getLogoName() : string
    {
        return (string) $this->getData('logo');
    }

    /**
     * Get the color the shop uses for ornamentation.
     *
     * @return string
     */
    public function getBackgroundColor() : string
    {
        return $this->getData('background_color');
    }

    /**
     * Get the color the shop uses for text.
     *
     * @return string
     */
    public function getFontColor() : string
    {
        return $this->getData('font_color');
    }

    /**
     * Is the user (current or specified) the owner.
     *
     * @param int $userId
     *
     * @return bool
     */
    public function isOwner(int $userId = null) : bool
    {
        $userId = $userId ?? auth()->id();

        return $this->owner->getId() === $userId;
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
