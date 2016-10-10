<?php

namespace Sneefr\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StatLogin extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stats_login';

    /**
     * Indicates if the model should be timestamped.
     *
     * This class does actually use a creation timestamp, but
     * it is handled without relying on Eloquent’s abilities.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'event'];

    /**
     * Log a new event for a given person.
     *
     * @param  int     $userId
     * @param  string  $event
     *
     * @return void
     */
    public static function log($userId, $event)
    {
        $model = new static(['user_id' => $userId, 'event' => $event]);

        $model->setCreatedAt(new Carbon);

        $model->save();
    }
}
