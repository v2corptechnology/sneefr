<?php

namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['shop_id', 'user_id', 'ad_id', 'status', 'is_positive', 'body'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['is_positive' => 'boolean'];

    /**
     * Filter only valid evaluations (evaluations that can be displayed).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeValid(Builder $query) : Builder
    {
        return $query->whereIn('status', ['given', 'forced']);
    }
}
