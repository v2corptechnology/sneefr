<?php namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tagged';

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
    protected $fillable = ['id', 'user_id', 'by_user_id', 'taggable_type', 'taggable_id'];

    public function taggable()
    {
        return $this->morphTo()->withTrashed();
    }

    public function by()
    {
        return $this->belongsTo(User::class, 'by_user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsAdAttribute()
    {
        return $this->taggable_type == Ad::class;
    }

    public function getIsSearchAttribute()
    {
        return $this->taggable_type == User::class;
    }
}
