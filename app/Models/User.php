<?php namespace Sneefr\Models;

use AlgoliaSearch\Laravel\AlgoliaEloquentTrait;
use Carbon\Carbon;
use Crypt;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Laracodes\Presenter\Traits\Presentable;
use Laravel\Cashier\Billable;
use Nicolaslopezj\Searchable\SearchableTrait;
use Sneefr\Events\UserEmailChanged;
use Sneefr\Events\UserRegistered;
use Sneefr\Jobs\VerifyEmail;
use Sneefr\Models\Traits\Likeable;
use Sneefr\PhoneNumber;
use Sneefr\Presenters\UserPresenter;
use Sneefr\Services\Gamificator;
use Sneefr\Traits\Encryptable;
use Sneefr\Traits\Follower;
use Sneefr\UserEvaluations;
use Sneefr\UserPayment;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use AlgoliaEloquentTrait;
    use Authenticatable, Authorizable, Encryptable, CanResetPassword, Follower, SoftDeletes, SearchableTrait, Billable, Presentable, Likeable;
    use LogsActivity;
    use Notifiable;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'given_name' => 10,
            'surname'    => 2
        ],
    ];

    protected $dates = ['birthdate', 'trial_ends_at', 'deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
        'plain',
        'token',
        'email',
        'facebook_email'
    ];

    /**
     * The attributes we can mass assign.
     *
     * @var array
     */
    protected $fillable = [
        'facebook_id',
        'facebook_email',
        'email',
        'email_verified',
        'location',
        'lat',
        'long',
        'surname',
        'given_name',
        'gender',
        'locale',
        'verified',
        'token',
        'birthdate',
        'gamification_objectives',
        'preferences',
        'data',
        'payment',
        'stripe_id',
        'card_brand',
        'card_last_four',
        'trial_ends_at',
        'phone',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'verified'                => 'bool',
        'email_verified'          => 'bool',
        'preferences'             => 'array',
        'data'                    => 'array',
        'payment'                 => 'array',
        'gamification_objectives' => 'array',
    ];

    /**
     * The attributes that needs to be logged by the LogsActivity trait.
     *
     * @var array
     */
    protected static $logAttributes = ['surname', 'given_name', 'email', 'email_verified', 'gender', 'verified', 'birthdate', 'phone', 'location', 'lat', 'long', 'preferences', 'gamification_objectives'];

    protected $encryptable = [
    ];

    /**
     * The presenter used by front-end for this model.
     *
     * @var \Laracodes\Presenter\Presenter
     */
    protected $presenter = UserPresenter::class;

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

    public static $perEnvironment = true;

    public $algoliaSettings = [
        'attributesToIndex' => [
            'surname',
            'given_name'
        ]
    ];

    public function getAlgoliaRecord()
    {
       return  [
        'id'          => $this->id,
        'given_name'  => $this->given_name,
        'surname'     => $this->surname,
        'facebook_id' => $this->facebook_id,
        'user_hash'    => $this->getRouteKey()
       ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function ads()
    {
        return $this->hasMany(Ad::class)->latest();
    }

    /**
     * User's shops relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function shops()
    {
        return $this->belongsToMany(Shop::class)->latest();
    }

    /**
     * @return mixed
     */
    public function shop()
    {
        return $this->hasOne(Shop::class)->withTrashed();
    }

    /**
     * User's shops relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function administrableShops()
    {
        return $this->belongsToMany(Shop::class)->latest()->withTrashed();
    }

    /**
     * User's places relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function places()
    {
        //return $this->belongsToMany(Place::class)->withTimestamps()->latest();
        return $this->morphedByMany(Place::class, 'followable')->withTimestamps()->latest();
    }

    public function scopeGeolocated($query)
    {
        return $query->whereNotNull('location');
    }

    public function scopeExceptStaff($query)
    {
        return $query->where('id', '>', 4);
    }

    public function scopeWithoutSilent($query)
    {
        $query->where('is_hidden_from_friends', 0);
    }

    public function scopeWithSilent($query)
    {
        $query->where('is_hidden_from_friends', 1);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function searches()
    {
        return $this->hasMany(Search::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->unread();
    }

    public function unreadMessages()
    {
        return $this->hasMany(Message::class, 'to_user_id')->unread();
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

    public function discussionsWithMe()
    {
        return $this->hasMany(Discussion::class, 'the_other_id', 'id');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * Get the User models that have initiated a
     * relationship targeting the current model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @deprecated
     */
    public function followers()
    {
        //return $this->belongsToMany(self::class, 'relationships', 'to_user_id', 'from_user_id');
        return $this->morphToMany(Self::class, 'followable')->withTimestamps();
    }

    /**
     * Get the User models which are targeted by relationships
     * initiated by the current model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followedUsers()
    {
        //return $this->belongsToMany(self::class, 'relationships', 'from_user_id', 'to_user_id');
        return $this->morphedByMany(Self::class, 'followable')->withTimestamps();

    }
    
    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referent_user_id');
    }

    /**
     * Relationship to the the shops the user is following.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followedShops()
    {
        //return $this->belongsToMany(Shop::class);
        return $this->morphedByMany(Shop::class, 'followable')->withTimestamps();
    }

    /**
     * Relationship to the evaluations of this user.
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
    public function getRouteKey()
    {
        $hashids = app('Hashids\Hashids');

        return $hashids->encode($this->getKey());
    }

    /**
     * Get id of the user.
     *
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get id of the user.
     *
     * @return int
     * @deprecated
     */
    public function id()
    {
        return $this->getId();
    }

    /**
     * Get social network identifier of the user.
     *
     * @return int
     */
    public function getSocialNetworkId()
    {
        return (int) $this->facebook_id;
    }

    /**
     * Get social network identifier of the user.
     *
     * @return int
     * @deprecated
     */
    public function socialNetworkId()
    {
        return $this->getSocialNetworkId();
    }

    /**
     *  Get if the status of the person has been verified.
     *
     * @return bool
     */
    public function isVerified()
    {
        return $this->verified;
    }

    public function getPlainAttribute($value)
    {
        return unserialize($value);
    }

    public function setPlainAttribute($value)
    {
        $this->attributes['plain'] = serialize($value);
    }

    public function getIsVerifiedAttribute()
    {
        return $this->verified;
    }

    public function getFormatedBirthdateAttribute()
    {
        if ($this->attributes['birthdate']) {
            return Carbon::createFromFormat('Y-m-d', $this->attributes['birthdate'])->format('d/m/Y');
        }
    }

    /**
     * If user is not geolocalized
     * and registered for more than 12 hours
     *
     * @return bool
     */
    public function getShowLocationDemandAttribute()
    {
        return ! $this->location &&
            Carbon::parse($this->created_at)->diffInHours(Carbon::now(), false) > 12;
    }

    /**
     * Accessor checking if this Facebook id is allowed to see the stats
     * @return bool
     */
    public function getCanSeeStatsAttribute()
    {
        return in_array($this->facebook_id, config('sneefr.staff_facebook_ids.administrators'));
    }

    /**
     * Accessor checking if this Facebook id is allowed to see logs section
     * @return bool
     */
    public function getCanSeeLogsAttribute()
    {
        return (bool) in_array($this->facebook_id, config('sneefr.staff_facebook_ids.developers'));
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function hasVerifiedEmail() : bool
    {
        return $this->email_verified;
    }

    /**
     * Accessor checking if this Facebook id is in the team
     * @return bool
     */
    public function getIsTeamAttribute()
    {
        return (bool) in_array($this->facebook_id, config('sneefr.staff_facebook_ids.team'));
    }

    /**
     * Get the list of achieved gamification objectives.
     *
     * @return array
     */
    public function getGamificationObjectives()
    {
        return (array) $this->gamification_objectives;
    }

    /**
     * Set the gamicitaion objectives of the user.
     *
     * @param array $objectives
     */
    public function setGamificationObjectives(array $objectives)
    {
        $this->gamification_objectives = $objectives;
    }

    /**
     * Get the rank name of the user.
     *
     * @return string
     * @deprecated
     */
    public function gamificationRank() : string
    {
        return $this->getRank();
    }

    /**
     * Get the gamification instance for this user.
     *
     * @return \Sneefr\Services\Gamificator
     */
    public function getGamificationAttribute() : Gamificator
    {
        return new Gamificator($this->getGamificationObjectives());
    }

    /**
     * Set the rank name of the user.
     *
     * @param string $rank
     */
    public function setRank(string $rank)
    {
        $this->data = array_merge((array) $this->data, ['rank' => $rank]);
    }

    /**
     * Get the rank name of the user.
     *
     * @return string
     */
    public function getRank() : string
    {
        return $this->data['rank'] ?? 'default';
    }

    /**
     * Get the language asked by this user.
     *
     * @return string
     */
    public function getLanguage() : string
    {
        $fallbackLocale = \Config::get('app.fallback_locale');

        return array_get($this, 'locale', $fallbackLocale);
    }

    /**
     * Get the location's name of this user.
     *
     * @return string|null
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Get the latitude of the user.
     *
     * @return float|null
     */
    public function getLatitude()
    {
        return $this->lat ?? null;
    }

    /**
     * Get the longitude of the user.
     *
     * @return float|null
     */
    public function getLongitude()
    {
        return $this->long ?? null;
    }

    /**
     * Accessor reflecting if this profile
     * wants to receive daily digest.
     *
     * @return bool
     */
    protected function getIsSubscribedToDailyDigestAttribute()
    {
        return (bool) isset($this->preferences['daily_digest']) && $this->preferences['daily_digest'];
    }

    /**
     * @return \Sneefr\UserPayment
     */
    public function payment() : UserPayment
    {
        return new UserPayment($this);
    }

    /**
     * Create or update a user account.
     *
     * @param array $data
     *
     * @return \Sneefr\Models\User
     */
    public static function register(array $data) : self
    {
        $data = self::normalize($data);

        $user = self::withTrashed()->where('facebook_id', $data['facebook_id'])->first();

        $verifyEmail = false;

        // Create a new user
        if (is_null($user)) {
            $user = self::create($data);
            $verifyEmail = true;
        } // The user re-activate its account
        elseif ($user->trashed()) {
            $user->restore();
            $user->update($data);
            $verifyEmail = true;
        } // This is a simple login, don't update everything
        else {
            if (!$user->hasVerifiedEmail() && ($user->getEmail() != $data['email'])) {
                $verifyEmail = true;
            }

            $user->update(Arr::except($data, ['email', 'preferences']));
        }

        // Mark this email as not verified
        if ($verifyEmail) {
            dispatch(new VerifyEmail($user));
        }

        event(new UserRegistered($user));

        return $user;
    }

    /**
     * Normalize data input to User fields.
     * 
     * @param array $data
     *
     * @return array
     */
    public static function normalize(array $data) : array
    {
        return [
            'facebook_id'         => $data['id'],
            'surname'             => $data['last_name'],
            'given_name'          => $data['first_name'],
            'verified'            => $data['verified'],
            'token'               => $data['access_token'],
            'locale'              => session()->get('lang'),
            'preferences'         => ['daily_digest' => true],
            'email'               => $data['email'] ?? null,
            'facebook_email'      => $data['email'] ?? null,
            'gender'              => $data['gender'] ?? null,
            'birthdate'           => isset($data['birthday']) ? Carbon::parse($data['birthday'])->toDateString() : null,
        ];
    }

    /**
     * Verify this user is part of the administrator team.
     *
     * @return bool
     */
    public function isAdmin() : bool
    {
        return in_array($this->getId(), config('sneefr.staff_user_ids', []));
    }

    public function getPhoneAttribute() : PhoneNumber
    {
        return new PhoneNumber($this->attributes['phone']);
    }
}
