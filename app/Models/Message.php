<?php

namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;
use Sneefr\Support\MessageCollection;

class Message extends Model
{

    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $dates = ['deleted_at', 'read_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'messages';

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
        'discussion_id',
        'from_user_id',
        'to_user_id',
        'body',
        'read_at'
    ];

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     *
     * @return \Sneefr\Support\MessageCollection
     */
    public function newCollection(array $models = []) : MessageCollection
    {
        return new MessageCollection($models);
    }

    public function from()
    {
        return $this->belongsTo(User::class, 'from_user_id')->withTrashed();
    }

    public function to()
    {
        return $this->belongsTo(User::class, 'to_user_id')->withTrashed();
    }

    public function discussion()
    {
        return $this->belongsTo(Discussion::class, 'discussion_id');
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeUnreadDiscussion($query)
    {
        return $query->groupBy('discussion_key')
            ->whereNull('read_at');
    }

    public function body()
    {
        // The Text you want to filter for urls
        $text = nl2br(strip_tags($this->attributes['body'], '<br>'));

        // Auto transform links into html anchors
        return auto_link($text);
    }
}
