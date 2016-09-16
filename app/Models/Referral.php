<?php namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'referrals';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'referent_user_id',
        'referred_user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }
}
