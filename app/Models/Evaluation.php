<?php

namespace Sneefr\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    const STATUS_FORCED = 'forced';
    const STATUS_PENDING = 'pending';
    const STATUS_GIVEN = 'given';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['shop_id', 'evaluator_id', 'ad_id', 'status', 'is_positive', 'body'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['is_positive' => 'boolean'];


    /**
     * Evaluations older than x days.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int                                   $days
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDaysOld(Builder $query, int $days = 1) : Builder
    {
        return $query->whereDate('created_at', '<', Carbon::now()->subDays($days)->toDateTimeString());
    }

    /**
     * Filter only pending evaluations.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending(Builder $query) : Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Filter only valid evaluations (evaluations that can be displayed).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeValid(Builder $query) : Builder
    {
        return $query->whereIn('status', [self::STATUS_FORCED, self::STATUS_GIVEN]);
    }
}
