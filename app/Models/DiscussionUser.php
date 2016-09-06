<?php namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;

class DiscussionUser extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /**
     * @var string The database table used by the model.
     */
    protected $table = 'discussion_users';

    /**
     * @var array Fields that must be handled as date.
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array The attributes we can mass assign.
     */
    protected $fillable = ['discussion_id', 'user_id'];
}
