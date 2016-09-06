<?php namespace Sneefr\Models;

use AlgoliaSearch\Laravel\AlgoliaEloquentTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Shop extends Model
{
    use AlgoliaEloquentTrait, LogsActivity, SoftDeletes;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Set whether or not Algolia has to auto-index
     * models when they are saved.
     *
     * @var bool
     */
    public static $autoIndex = true;

    /**
     * Whether or not Algolia has to append environment name to index.
     * Ie.: shops_staging
     *
     * @var bool
     */
    public static $perEnvironment = true;


    /**
     * Algolia's specific indexing settings.
     *
     * @var array
     */
    public $algoliaSettings = [
        'attributesToIndex' => [
            'id',
            'slug',
            'data',
        ],
        'customRanking' => [
            'desc(user_id)',
        ],
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shops';

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

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship to the owner of the shop.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function owners()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Relationship to the administrators of the shop.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function admins()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
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
     * Relationship to the employees of the shop.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function employees()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Relationship to the followers of the shop.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function followers()
    {
        return $this->morphToMany(User::class, 'followable')->latest()->withTimestamps();
    }

    /**
     * Relationship from the shop to its discussions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discussions()
    {
        return $this->hasMany(Discussion::class, 'shop_id')->latest();
    }

    /**
     * Relationship to the evaluations of this shop.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function evaluations()
    {
        return $this->morphMany(Evaluation::class, 'evaluated');
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
        return $this->getData('description');
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
     * Check if this user is following the shop.
     *
     * @param int $userId
     *
     * @return bool
     */
    public function isFollowed(int $userId = null) : bool
    {
        $userId = $userId ?? auth()->id();

        return in_array($userId, $this->followers->lists('id')->all());
    }
}
