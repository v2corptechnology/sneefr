<?php namespace Sneefr\Models;

use AlgoliaSearch\Laravel\AlgoliaEloquentTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Img;
use Laracodes\Presenter\Traits\Presentable;
use Nicolaslopezj\Searchable\SearchableTrait;
use Sneefr\Delivery;
use Sneefr\Models\Traits\Likeable;
use Sneefr\Models\Traits\StaffFilterable;
use Sneefr\Presenters\AdPresenter;
use Sneefr\Price;
use Spatie\Activitylog\Traits\LogsActivity;

class Ad extends Model
{
    use AlgoliaEloquentTrait;
    use Likeable;
    use LogsActivity;
    use SearchableTrait;
    use SoftDeletes;
    use StaffFilterable;
    use Presentable;

    public $sellerEvaluationRatio;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'title' => 10,
            'description' => 5
        ],
    ];

    protected $dates = ['deleted_at'];

    protected $presenter = AdPresenter::class;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ads';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['latitude', 'longitude'];

    /**
     * The attributes we can mass assign.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'shop_id',
        'category_id',
        'title',
        'description',
        'amount',
        'currency',
        'delivery',
        'transaction',
        'location',
        'latitude',
        'longitude',
        'images',
        'locked_for',
        'final_amount',
        'is_secure_payment',
        'sold_to',
        'condition_id',
        'is_hidden_from_friends',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that needs to be logged by the LogsActivity trait.
     *
     * @var array
     */
    protected static $logAttributes = ['shop_id', 'category_id', 'condition_id', 'title', 'description', 'amount', 'location', 'latitude', 'longitude', 'images', 'final_amount'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_secure_payment' => 'boolean',
        'images'            => 'array',
        'transaction'       => 'array',
        'amount'            => 'float',
        'latitude'          => 'float',
        'longitude'         => 'float',
        'category_id'       => 'int',
        'condition_id'      => 'int',
    ];

    /*
     * Algolia related config
     */

    /**
     * Set whether or not Algolia has to auto-index
     * models when they are saved.
     *
     * @var bool
     */
    public static $autoIndex = true;

    public static $perEnvironment = true; // ads_{\App::environnement()}';

    public $algoliaSettings = [
        'attributesToIndex' => [
            'title',
            'description'
        ],
        'customRanking' => [
            'desc(condition_id)'
        ],
        'attributesForFaceting' => [
            'category_id'
        ],
        'slaves' => [
            'ads_by_price_desc',
            'ads_by_price_asc',
            'ads_by_date_desc',
            'ads_by_date_asc',
            'ads_by_condition_desc',
            'ads_by_condition_asc',
            'ads_by_evaluation_desc',
            'ads_by_evaluation_asc',
        ],
    ];

    public $slavesSettings = [
        'ads_by_price_desc' => [
            'customRanking' => [
                'desc(amount)'
            ]
        ],
        'ads_by_price_asc' => [
            'customRanking' => [
                'asc(amount)'
            ]
        ],
        'ads_by_date_desc' => [
            'customRanking' => [
                'desc(created_at_timestamp)'
            ]
        ],
        'ads_by_date_asc' => [
            'customRanking' => [
                'asc(created_at_timestamp)'
            ]
        ],
        'ads_by_condition_desc' => [
            'customRanking' => [
                'desc(condition_id)'
            ]
        ],
        'ads_by_condition_asc' => [
            'customRanking' => [
                'asc(condition_id)'
            ]
        ],
        'ads_by_evaluation_desc' => [
            'customRanking' => [
                'desc(evaluationRatio)'
            ]
        ],
        'ads_by_evaluation_asc' => [
            'customRanking' => [
                'asc(evaluationRatio)'
            ]
        ]
    ];

    public function getAlgoliaRecord()
    {
        return array_merge($this->toArray(), [
            'evaluationRatio' => $this->seller->evaluations->ratio(),
            '_geoloc' => [
                "latitude" => $this->getLatitude(),
                "longitude" => $this->getLongitude()
            ],
            'created_at' => $this->created_at->timestamp,
            'updated_at' => $this->updated_at->timestamp,
        ]);
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

    public function soldTo()
    {
        return $this->belongsTo(User::class, 'sold_to');
    }

    public function actions()
    {
        return $this->morphMany('Action', 'actionable');
    }

    public function tags()
    {
        return $this->hasMany(Tag::class, 'taggable_id')->where('taggable_type', Ad::class);
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function scopeSold($query)
    {
        return $query->onlyTrashed()->whereNotNull('sold_to');
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
        return $this->onlyTrashed()->whereNotNull('sold_to');
    }

    public function scopeDeletedWithoutSold()
    {
        return $this->onlyTrashed()->whereNull('sold_to');
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
     * Accessor indicating if this ad is sold to someone
     *
     * @return bool
     */
    public function getIsSoldAttribute()
    {
        return (bool) $this->sold_to && $this->deleted_at;
    }

    /**
     * Accessor indicating if this ad is deleted without being sold
     *
     * @return bool
     */
    public function getIsDeletedAttribute()
    {
        return (bool) ! $this->sold_to && $this->deleted_at;
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
     * Get the category identifier.
     *
     * @return int
     */
    public function categoryId() : int
    {
        return (int) $this->category_id;
    }

    /**
     * Get the condition identifier of the ad.
     *
     * @return int
     */
    public function getConditionId() : int
    {
        return $this->condition_id;
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
     *
     * @return array
     */
    public function images($dimensions = 120)
    {
        $images = [];

        foreach ($this->imageNames() as $image) {
            $images[] = Img::thumbnail($this, $image, $dimensions);
        }

        return $images;
    }

    /**
     * Is this ad locked ?
     *
     * @return bool
     */
    public function isLocked() : bool
    {
        return (bool) $this->locked_for;
    }

    /**
     * Check if this ad is marked as private
     *
     * @return bool
     */
    public function isHiddenFromFriends()
    {
        return (bool) $this->is_hidden_from_friends;
    }

    /**
     * Is this ad locked for this user ?
     *
     * @return bool
     */
    public function isLockedFor(int $userId) : bool
    {
        return $this->locked_for == $userId;
    }

    /**
     * Is this ad requiring a secure payment ?
     *
     * @return bool
     */
    public function isSecurePaymentAsked()
    {
        return (bool) $this->is_secure_payment;
    }

    /**
     * Lock this ad.
     *
     * @param int $userId
     *
     * @return $this
     */
    public function lockFor(int $userId) : \Sneefr\Models\Ad
    {
        $this->locked_for = $userId;
        $this->save();

        return $this;
    }

    /**
     * Unlock this ad.
     *
     * @return AdContract
     */
    public function unlock() : \Sneefr\Models\Ad
    {
        $this->locked_for = null;
        $this->final_amount = null;
        $this->save();

        return $this;
    }

    /**
     * Mark this ad as sold to a user identifier.
     *
     * @param int $userId
     *
     * @return AdContract
     */
    public function sellTo(int $userId) : \Sneefr\Models\Ad
    {
        $this->sold_to = $userId;
        $this->save();

        return $this;
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
        return new Price($this->amount, $this->currency, $this->delivery);
    }

    /**
     * Get the negotiated price object.
     *
     * @return \Sneefr\Price
     */
    public function negotiatedPrice() : Price
    {
        $amount = $this->final_amount ?? $this->amount;

        return new Price($amount, $this->currency, $this->delivery);
    }

    public function canMakeSecurePayement(): bool
    {
        return (bool) (($this->isSecurePaymentAsked() || ($this->isInShop() && $this->seller->payment()->hasOne())) && $this->amount >= 100 );
    }
}
