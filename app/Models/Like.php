<?php namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sneefr\Support\LikeCollection;

/**
 * Handle ‘likes’ that are done on various types of items.
 */
class Like extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'likes';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'likeable_id',
        'likeable_type',
        'user_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get all of the owning likeable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function likeable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user who liked the item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     *
     * @return \Sneefr\Support\LikeCollection
     */
    public function newCollection(array $models = []) : LikeCollection
    {
        return new LikeCollection($models);
    }
}
