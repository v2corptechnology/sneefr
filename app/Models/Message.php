<?php

namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The attributes we can mass assign.
     *
     * @var array
     */
    protected $fillable =  ['ad_id', 'body'];

    /**
     * Default values.
     *
     * @var array
     */
    protected $attributes = ['is_sent' => false];

    /**
     * Relationship with the ad the message refers to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}
