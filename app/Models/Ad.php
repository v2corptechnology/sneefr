<?php

namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Img;
use Laracodes\Presenter\Traits\Presentable;
use Laravel\Scout\Searchable;
use Sneefr\Delivery;
use Sneefr\Presenters\AdPresenter;
use Sneefr\Price;
use Spatie\Activitylog\Traits\LogsActivity;

class Ad extends Model
{
    use LogsActivity;
    use SoftDeletes;
    use Presentable;
    use Searchable;

    public $sellerEvaluationRatio;

    protected $dates = ['deleted_at'];

    protected $presenter = AdPresenter::class;

    /**
     * The attributes we can mass assign.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'shop_id', 'data', 'remaining_quantity', 'title', 'description', 'amount', 'final_amount', 'currency', 'delivery', 'location', 'latitude', 'longitude', 'images'];

    /**
     * The attributes that needs to be logged by the LogsActivity trait.
     *
     * @var array
     */
    protected static $logAttributes = ['shop_id', 'title', 'description', 'amount', 'location', 'latitude', 'longitude', 'images', 'final_amount'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'images'      => 'array',
        'transaction' => 'array',
        'amount'      => 'float',
        'latitude'    => 'float',
        'longitude'   => 'float',
        'data'        => 'array',
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $record = $this->toArray();
        $record['_geoloc'] = [
            'lat' => $this->getLatitude(),
            'lng' => $this->getLongitude(),
        ];
        $record['evaluationRatio'] = $this->shop->evaluations->ratio();

        return $record;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation to the owner of this ad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation to the user who bought this ad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'sold_to');
    }

    /**
     * Relation to the user this ad is locked for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lockedFor()
    {
        return $this->belongsTo(User::class, 'locked_for');
    }

    /**
     * Tags applied to this shop.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'item_tag', 'item_id');
    }

    /**
     * Relation to the shop of this ad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Relation to the evaluation of this ad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function evaluation()
    {
        return $this->hasOne(Evaluation::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function actions()
    {
        return $this->morphMany('Action', 'actionable');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function scopeSold($query)
    {
        return $query->onlyTrashed()->where('remaining_quantity', 0);
    }

    public function scopeExceptAdmin($query)
    {
        return $query->where(function ($q) {
            $q->where('is_admin', true)->orWhereNull('user_id');
        });

    }

    /**
     * Alias of ‘sold’ scope.
     *
     * This one uses a slightly more explicit name and ensure
     * we’ll never fetch any soft-deleted element.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeOnlySold()
    {
        // Explicitly narrow to only soft-deleted elements.
        return $this->onlyTrashed()->where('remaining_quantity', 0);
    }

    public function scopeDeletedWithoutSold()
    {
        return $this->onlyTrashed()->where('remaining_quantity', '>', 0);
    }

    public function scopeInArray($query, $ids)
    {
        return $query->whereIn('user_id', $ids);
    }

    public function scopeNotInArray($query, $ids)
    {
        return $query->whereNotIn('user_id', $ids);
    }

    public function scopeFilter($query, $filter)
    {
        switch ($filter) {
            case 'person':
                return $query->where('is_pro', 0);
                break;
            case 'pro':
                return $query->where('is_pro', 1);
                break;
            default:
                return $query;
                break;
        }
    }

    /**
     * Scope to order by random.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeOrderByRandom($query)
    {
        static $randomFunctions = [
            'mysql' => 'RAND()',
            'pgsql' => 'RANDOM()',
            'sqlite' => 'RANDOM()',
            'sqlsrv' => 'NEWID()',
        ];

        $driver = $this->getConnection()->getDriverName();

        return $query->orderByRaw($randomFunctions[$driver]);
    }

    /**
     * Get the value of the model's route key.
     *
     * @return string
     */
    public function getRouteKey()
    {
        return $this->getSlug();
    }

    public function getTitleAttribute()
    {
        return htmlspecialchars($this->attributes['title'], ENT_QUOTES, false);
    }

    /**
     * Do the current user likes the ad.
     *
     * @param int $userId
     *
     * @return bool
     */
    public function isLiked(int $userId = null) : bool
    {
        $userId = $userId ?: auth()->id();

        return (bool) $this->likes->where('user_id', $userId)->count();
    }

    /**
     * Do the current user has reported the ad.
     *
     * @param int $userId
     *
     * @return bool
     */
    public function isReported(int $userId = null) : bool
    {
        $userId = $userId ?: auth()->id();

        return (bool) $this->reports->where('user_id', $userId)->count();
    }

    /**
     * Get the ad description with line breaks.
     *
     * @return string
     */
    public function rawDescription() : string
    {
        return strip_tags($this->attributes['description'], '<br>');
    }

    /**
     * Get the ad description with line breaks.
     *
     * @return string
     */
    public function description() : string
    {
        return nl2br($this->rawDescription());
    }

    /**
     * Get the ad description without line breaks.
     *
     * @return string
     */
    public function oneLineDescription() : string
    {
        return strip_tags(nl2br($this->attributes['description']));
    }

    /**
     * Don't know if we must integrate it into the interface
     * since we could get it with $ad->seller()->id() and
     * one more query
     *
     * @deprecated
     * @return int
     */
    public function sellerId() : int
    {
        return (int) $this->user_id;
    }

    /**
     * Get the ad first image URL.
     *
     * @param mixed $dimensions (see parseDimensions())
     * @param bool  $crop
     *
     * @return string
     */
    public function firstImageUrl($dimensions = 120, bool $crop = true) : string
    {
        $names = $this->imageNames();

        $name = array_shift($names);

        return $crop
            ? Img::cropped($this, $name, $dimensions)
            : Img::thumbnail($this, $name, $dimensions);
    }

    /**
     * Am I the owner of this ad.
     *
     * @return bool
     */
    public function isMine() : bool
    {
        return auth()->check() && $this->user_id === auth()->id();
    }

    /**
     * Get the slug for this ad.
     *
     * @return string
     * @deprecated
     */
    public function slug() : string
    {
        return str_slug($this->id . ' ' . $this->title);
    }

    /**
     * Get the ad location's name.
     *
     * @return string
     */
    public function location() : string
    {
        return (string) $this->location;
    }

    /**
     * Get the ad location's latitude.
     *
     * @return float
     */
    public function latitude() : float
    {
        return (float) $this->latitude;
    }

    /**
     * Get the ad location's longitude.
     *
     * @return float
     */
    public function longitude() : float
    {
        return (float) $this->longitude;
    }

    /**
     * Get all images names for the ad.
     *
     * @return array
     */
    public function imageNames() : array
    {
        return (array) $this->images;
    }

    /**
     * Get image urls for this ad.
     *
     * @param mixed $dimensions (120, [120, 120], 120x120)
     * @param bool  $crop
     *
     * @return array
     */
    public function images($dimensions = 120, bool $crop = false)
    {
        $images = [];

        foreach ($this->imageNames() as $image) {
            if ($crop) {
                $images[] = Img::cropped($this, $image, $dimensions);
            } else {
                $images[] = Img::thumbnail($this, $image, $dimensions);
            }
        }

        return $images;
    }


    /**
     * Get the identifier of the ad.
     *
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get the title of the ad.
     *
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * Isser to check whether the ad is in a shop or not.
     *
     * @return bool
     */
    public function isInShop() : bool
    {
        return (bool) $this->shop_id;
    }

    /**
     * Get the identifier of the shop this ad belongs to.
     *
     * @return int
     */
    public function getShopId() : int
    {
        return (int) $this->shop_id;
    }

    /**
     * Get the description of the ad.
     *
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * Get the slug url of the ad.
     *
     * @return string
     */
    public function getSlug() : string
    {
        return str_slug($this->id . ' ' . $this->title);
    }

    /**
     * Get the latitude of the ad.
     *
     * @return float|null
     */
    public function getLatitude()
    {
        return $this->latitude ?? null;
    }

    /**
     * Get the longitude of the ad.
     *
     * @return float|null
     */
    public function getLongitude()
    {
        return $this->longitude ?? null;
    }

    /**
     * Get the distance (meters) separating the
     * current logged user and the ad.
     *
     * @return int|null
     */
    public function getDistance()
    {
        if (! $this->distanceCanBeCalculated()) {
            return null;
        }

        return $this->getMostRelevantDistance();
    }

    public function getSellerEvaluationRatio() : float
    {
        return $this->sellerEvaluationRatio;
    }

    public function setSellerEvaluationRatio(float $ratio)
    {
        $this->sellerEvaluationRatio = $ratio;
    }

    /**
     * Has the user or the ad, the minimum required to calculate a distance ?
     *
     * @return bool
     */
    protected function distanceCanBeCalculated() : bool
    {
        return $this->distance || $this->userIsGeolocated();
    }

    /**
     * Choose to use the ip-coords or user-coords before
     * returning the distance with them.
     *
     * @return int
     */
    protected function getMostRelevantDistance() : int
    {
        if ($this->distance) {
            return $this->distance;
        }

        $user = auth()->user();

        return vincentyGreatCircleDistance($user->lat, $user->long, $this->latitude, $this->longitude);
    }

    /**
     * Check if the current user is geolocated.
     *
     * @return bool
     */
    protected function userIsGeolocated() : bool
    {
        return auth()->check() && auth()->user()->lat && auth()->user()->long;
    }

    /**
     * Build the JSON object with fees.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public static function normalizeDeliveryOptions(Request $request)
    {
        $fees = collect(array_map(function ($destination) use ($request) {

            $price = $request->get('delivery_' . $destination . '_value');

            $price = $price ? intval($price * 100) : null;

            return [$destination => $price];

        }, $request->get('delivery', [])));

        $delivery = [
            'fees'     => $fees->collapse()->toArray(),
            'currency' => $request->get('delivery_currency'),
        ];

        return json_encode($delivery);
    }

    public function getDeliveryAttribute()
    {
        try{
            $data = json_decode($this->attributes['delivery'], true);
        }catch (\Exception $e){
            $data = $this->attributes['delivery'];
        }

        return new Delivery( (array) $data);
    }

    /**
     * Get the price object.
     *
     * @return \Sneefr\Price
     */
    public function price() : Price
    {
        return new Price($this->amount, $this->currency);
    }

    public function canMakeSecurePayement(): bool
    {
        return (bool) (($this->isInShop() && $this->seller->payment()->hasOne()) && $this->amount >= 100 );
    }
}
