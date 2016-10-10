<?php

namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes we can mass assign.
     *
     * @var array
     */
    protected $fillable = ['ad_id', 'buyer_id', 'seller_id', 'stripe_data', 'details'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['stripe_data' => 'array', 'details' => 'array'];
}
