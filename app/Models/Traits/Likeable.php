<?php namespace Sneefr\Models\Traits;

use Sneefr\Models\Like;

trait Likeable
{
    /**
     * Boot the soft taggable trait for a model.
     *
     * @return void
     */
    public static function bootLikeable()
    {
        if (static::removeLikesOnDelete()) {
            static::deleting(function($model) {
                $model->removeLikes();
            });
        }
    }

    /**
     * Fetch records that are liked by a given user.
     *
     * Ex: Book::whereLikedBy(123)->get();
     */
    public function scopeWhereLikedBy($query, $userId=null)
    {
        if(is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        return $query->whereHas('likes', function($q) use($userId) {
            $q->where('user_id', '=', $userId);
        });
    }

    /**
     * Get the collection of likes related to this record.
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Add a like for this record by the given user.
     * @param $userId mixed - If null will use currently logged in user.
     */
    public function like($userId=null)
    {
        if(is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        $like = $this->likes()
            ->where('user_id', '=', $userId)
            ->first();

        if($like) return;

        $like = new Like();
        $like->user_id = $userId;
        $this->likes()->save($like);

        return $like;
    }

    /**
     * Remove a like from this record for the given user.
     * @param $userId mixed - If null will use currently logged in user.
     */
    public function unlike($userId=null)
    {
        if(is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        $like = $this->likes()
            ->where('user_id', '=', $userId)
            ->first();

        if(!$like) { return; }

        $like->delete();

        return $like;
    }

    /**
     * Has the currently logged in user already "liked" the current object
     *
     * @param string $userId
     * @return boolean
     */
    public function liked($userId=null)
    {
        if(is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        return (bool) $this->likes()
            ->where('user_id', '=', $userId)
            ->count();
    }

    /**
     * Fetch the primary ID of the currently logged in user
     * @return number
     */
    public function loggedInUserId()
    {
        return auth()->id();
    }

    /**
     * Did the currently logged in user like this model
     * Example : if($book->liked) { }
     * @return boolean
     */
    public function getLikedAttribute()
    {
        return $this->liked();
    }

    public function getPayload() : string
    {
        $serialized = serialize([
            'classname' => get_class($this),
            'id'        => $this->id,
        ]);

        return \Crypt::encrypt($serialized);
    }

    /**
     * Should remove likes on model row delete (defaults to true)
     * public static removeLikesOnDelete = false;
     */
    public static function removeLikesOnDelete()
    {
        return isset(static::$removeLikesOnDelete)
            ? static::$removeLikesOnDelete
            : true;
    }

    /**
     * Delete likes related to the current record
     */
    public function removeLikes()
    {
        Like::where('likeable_type', $this->morphClass)->where('likeable_id', $this->id)->delete();
    }
}
