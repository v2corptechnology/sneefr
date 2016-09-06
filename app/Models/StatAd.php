<?php namespace Sneefr\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StatAd extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stats_ad';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes we can mass assign.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'viewed_id'];

    public function setUpdatedAtAttribute($value)
    {
        // Do nothing.
    }
}
