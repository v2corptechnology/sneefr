<?php

namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    /**
     * The attributes we can mass assign.
     *
     * @var array
     */
    public $fillable = ['ad_id', 'initial', 'remaining'];
}
