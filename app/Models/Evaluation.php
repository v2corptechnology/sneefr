<?php

namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sneefr\Support\EvaluationCollection;

class Evaluation extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'evaluations';

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
    protected $fillable = [
        'user_id',
        'evaluated_id',
        'evaluated_type',
        'ad_id',
        'status',
        'value',
        'body',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    /**
     * evaluated Shop or User relation
     * @return Object
     */
    public function evaluated()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function scopeType($query, $type)
    {
        if($type == "shop")
        {
            return $query->where('evaluated_type', Shop::class);
        }
        return $query->where('evaluated_type', User::class);
    }

    public function scopeValid($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'valid')->orWhere('status', 'forced');
        });
    }

    public function scopeNegative($query)
    {
        return $query->where('value', 0);
    }

    public function scopePositive($query)
    {
        return $query->where('value', 1);
    }

    public function getIsForcedAttribute()
    {
        return $this->status == 'forced';
    }

    public function getIsWaitingAttribute()
    {
        return $this->status == 'waiting';
    }

    public function getIsValidatedAttribute()
    {
        return $this->status == 'valid';
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     *
     * @return \Sneefr\Support\EvaluationCollection
     */
    public function newCollection(array $models = []) : EvaluationCollection
    {
        return new EvaluationCollection($models);
    }
}
