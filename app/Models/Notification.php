<?php namespace Sneefr\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

    protected $dates = ['read_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

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
    protected $fillable = ['user_id', 'notifiable_type', 'notifiable_id', 'is_special', 'read_at'];

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Limit notifications to "normal" only.
     *
     * @param $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNormal($query)
    {
        return $query->where('is_special', 0);
    }

    /**
     * Limit notifications to "special" only.
     *
     * @param $query
     *
     * \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSpecial($query)
    {
        return $query->where('is_special', 1);
    }

    /**
     * Define relationship to the user notified.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notifiable()
    {
        return $this->morphTo()->withTrashed();
    }

    /**
     * Accessor for the notification identifier
     *
     * @return int
     */
    public function id(){
        return $this->id;
    }

    /**
     * Isser to tell if the notificiation is unread
     *
     * @return bool
     */
    public function isUnread()
    {
        return is_null($this->read_at);
    }

    /**
     * Check if this notification was flagged as special
     *
     * @return bool
     */
    public function isSpecial() : bool
    {
        return $this->is_special;
    }

    /**
     * Accessor for the created at date
     *
     * @return int
     */
    public function createdAt(){
        return $this->created_at;
    }
}
