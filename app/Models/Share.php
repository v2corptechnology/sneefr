<?php namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shares';

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
    protected $fillable = ['user_id', 'ad_id', 'type'];
}
