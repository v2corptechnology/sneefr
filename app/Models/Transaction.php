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

    /**
     * The ad relationship of the transaction.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ad()
    {
        return $this->belongsTo(Ad::class)->withTrashed();
    }

    /**
     * The seller relationship of the transaction.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function seller()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * The buyer relationship of the transaction.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function buyer()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
