<?php namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;
use Sneefr\Models\Traits\Likeable;
use Sneefr\Support\FollowCollection;

class Follow extends Model
{
    use Likeable;

    protected $table = 'followables';

    protected $fillable = ['user_id', 'followable_id', 'followable_type'];

    public function newCollection(array $models = [])
    {
        return new FollowCollection($models);
    }

    /**
     * User that is following the followable object
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'followable_id');
    }

    public function initiator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Object that is being followed by the user above
     *
     * @return object
     */
    public function followable()
    {
        return $this->morphTo();
    }
}
