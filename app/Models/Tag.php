<?php

namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['alias', 'title'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['yelp_data' => 'array'];

    public function shops()
    {
        return $this->belongsToMany(Shop::class);
    }
}
