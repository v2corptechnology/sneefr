<?php

namespace Sneefr\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laracodes\Presenter\Traits\Presentable;
use Laravel\Cashier\Billable;
use Sneefr\PhoneNumber;
use Sneefr\UserEvaluations;
use Sneefr\UserPayment;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, LogsActivity, SoftDeletes, Billable, Presentable, Notifiable;

    protected $dates = ['birthdate', 'trial_ends_at', 'deleted_at'];

    /**
     * The attributes we can mass assign.
     *
     * @var array
     */
    protected $fillable = [
        'avatar',
        'facebook_id',
        'facebook_email',
        'email',
        'email_verified',
        'password',
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
        'verified'       => 'bool',
        'email_verified' => 'bool',
        'data'           => 'array',
        'payment'        => 'array',
    ];

    /**
     * The attributes that needs to be logged by the LogsActivity trait.
     *
     * @var array
     */
    protected static $logAttributes = ['surname', 'given_name', 'email', 'email_verified', 'gender', 'verified', 'birthdate', 'phone', 'location', 'lat', 'long'];

    /**
     * The presenter used by front-end for this model.
     *
     * @var \Laracodes\Presenter\Presenter
     */
    protected $presenter = \Sneefr\Presenters\UserPresenter::class;

    public function ads()
    {
        return $this->hasMany(Ad::class)->latest();
    }

    /**
     * @return mixed
     */
    public function shop()
    {
        return $this->hasOne(Shop::class)->withTrashed();
    }

    /**
     * Check if this user has at least one shop.
     *
     * @return bool
     */
    public function hasShop() : bool
    {
        return (bool) $this->shop()->count();
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    /**
     * All the sales or purchases of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recentDeals()
    {
        return $this->hasMany(Transaction::class, 'buyer_id')
            ->orWhere('seller_id', $this->id)
            ->whereDate('created_at', '>=', \Carbon\Carbon::now()->subYear(1)->toDateString())
            ->with('seller', 'buyer', 'ad', 'ad.shop')
            ->latest();
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
     * Get social network identifier of the user.
     *
     * @return int
     */
    public function getSocialNetworkId() : int
    {
        return (int) $this->facebook_id;
    }

    /**
     *  Get if the status of the person has been verified.
     *
     * @return bool
     */
    public function isVerified() : bool
    {
        return (bool) $this->verified;
    }

    /**
     * Get the user's email.
     *
     * @return string
     */
    public function getEmail() : string
    {
        return (string) $this->email;
    }

    /**
     * Check if the current email is already verified.
     *
     * @return bool
     */
    public function hasVerifiedEmail() : bool
    {
        return (bool) $this->email_verified;
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
     * @return \Sneefr\UserPayment
     */
    public function payment() : UserPayment
    {
        return new UserPayment($this);
    }

    /**
     * Verify this user is part of the administrator team.
     *
     * @return bool
     */
    public function isAdmin() : bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * Get user's phone number
     *
     * @return \Sneefr\PhoneNumber
     */
    public function getPhoneAttribute() : PhoneNumber
    {
        return new PhoneNumber($this->attributes['phone']);
    }

    /**
     * Get the url for the logo, given the dimensions.
     *
     * @param mixed $dimensions
     *
     * @return string
     */
    public function getPicture($dimensions = '80x80') : string
    {
        return \Img::avatar($this->getSocialNetworkId(), $dimensions);
    }
}
