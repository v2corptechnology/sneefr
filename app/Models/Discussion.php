<?php

namespace Sneefr\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sneefr\Models\Traits\Likeable;
use Sneefr\Support\DiscussionCollection;

class Discussion extends Model
{
    use Likeable;

    /**
     * @var string The database table used by the model.
     */
    protected $table = 'discussions';

    /**
     * The attributes we can mass assign.
     *
     * @var array
     */
    protected $fillable = ['shop_id'];

    public function getRouteKey()
    {
        return $this->id();
    }

    /**
     * Relationship to the shop of the discussion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the participants of this discussion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'discussion_users')
            ->withTrashed()
            ->withTimestamps();
    }

    /**
     * Check if is a shop discussion or not
     * 
     * @return bool
     */
    public function isShopDiscussion(): bool
    {
        return (bool) ($this->shop_id) ;
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     *
     * @return \Sneefr\Support\DiscussionCollection
     */
    public function newCollection(array $models = []) : DiscussionCollection
    {
        return new DiscussionCollection($models);
    }

    /**
     * Get participants of the discussion.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function participantsList()
    {
        return $this->participants()->get();
    }

    /**
     * Get the discussion identifier.
     *
     * @return int
     */
    public function id()
    {
        return (int) $this->id;
    }

    /**
     * Get the messages from the discussion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the messages to this recipient from the discussion.
     *
     * @param int $userId User identifier to show only those messages
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function messagesOnlyTo($userId)
    {
        $messages = $this->messages()->get();

        $filteredMessages = $messages->reject(function ($message) use ($userId) {
            return $message->to_user_id != $userId;
        });

        return $filteredMessages;
    }

    /**
     * Mark the messages to the current discussion reader as read.
     *
     * @return bool
     */
    public function markMessagesAsRead()
    {
        $nbUpdated = Message::where('discussion_id', $this->id())
            ->where('to_user_id', \Auth::user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => Carbon::now()]);

        if ($nbUpdated) {
            $this->touch();
        }

        return (bool) $nbUpdated;
    }

    /**
     * Get the recipient of this discussion.
     *
     * @return \Sneefr\Models\User
     */
    public function recipient()
    {
        return $this->participants->filter(function ($user) {
            return $user->id != auth()->id();
        })->first();
    }

    /**
     * Store a message in the discussion.
     *
     * @param array $data The fields we receive
     *
     * @return \Sneefr\Models\Message|false
     */
    public function post(array $data)
    {
        $message = new Message();
        $message->discussion_id = $this->id();
        $message->from_user_id = auth()->user()->id;
        $message->to_user_id = $data['to_user_id'];
        $message->body = $data['body'];

        if ($message->save()) {
            $this->touch();
            return $message;
        }

        return false;
    }

    /**
     * Ads that are in this discussion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ads()
    {
        return $this->belongsToMany(Ad::class, 'discussion_ads')
            ->withTimestamps()
            ->whereNull('discussion_ads.deleted_at')
            ->withPivot(['deleted_at']);
    }

    /**
     * Ads deleted or not, that are in this discussion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function allAds()
    {
        return $this->belongsToMany(Ad::class, 'discussion_ads')
            ->withTimestamps()
            ->withPivot(['deleted_at']);
    }

    /**
     * List the unlocked ads that belongs to me and are in this discussion.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function myUnlockedAds()
    {
        return $this->ads()->get()->filter(function($discussedAd) {
            return $discussedAd->isMine();
        });
    }

    /**
     * Get the ads this discussion is speaking about.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function discussedAds()
    {
        // TODO: Check why this fucking relationship doesn't work
        //return $this->ads()->get();
        return DiscussedAd::where('discussion_id', $this->id())->get();
    }

    /**
     * Ad an at to this discussion.
     *
     * @param int $adId The Ad identifier
     *
     * @return \Sneefr\Models\DiscussedAd
     */
    public function discussAd($adId)
    {
        $this->touch();

        $discussedAd = DiscussedAd::withTrashed()
            ->where('discussion_id', $this->id())
            ->where('ad_id', $adId)
            ->first();

        if ($discussedAd == null) {
            $discussedAd = DiscussedAd::create([
                'discussion_id' => $this->id(),
                'ad_id' => $adId,
            ]);
        } elseif ($discussedAd->trashed()) {
            $discussedAd->restore();
        } elseif ($discussedAd->exists) {
            $discussedAd->touch();
        }

        return $discussedAd;
    }

    /**
     * Get the messages and the ads from a discussion.
     *
     * @return \Illuminate\Support\Collection
     */
    public function contents()
    {
        // toBase() transforms Eloquent Collection to normal once.
        // So the merge() doesn't merge same identifiers.
        $messages = $this->messages()->get()->toBase();
        $ads = $this->allAds()->withTrashed()->get()->toBase();

        $contents = $messages->merge($ads);

        $contents = $contents->sort(function($itemA, $itemB) {
            $sortA = $this->getSortValue($itemA);
            $sortB = $this->getSortValue($itemB);

            return $sortA > $sortB;
        });

        return $contents;
    }

    /**
     * Remove an Ad from this discussion.
     *
     * @param int $adId The Ad identifier
     *
     * @return bool
     */
    public function removeAd($adId)
    {
        $this->touch();

        return (bool) DiscussedAd::where('ad_id', $adId)
            ->where('discussion_id', $this->id())
            ->delete();
    }

    /**
     * Check if this discussion is locked to me.
     *
     * @return bool
     */
    public function isLockedForMe()
    {
        return (bool) ! $this->messagesOnlyTo(auth()->user()->id)->count();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $item
     *
     * @return \DateTime
     */
    protected function getSortValue(Model $item)
    {
        $itemClass = get_class($item);

        if ($itemClass === Message::class) {
            return $item->created_at;
        } elseif (Ad::class) {
            if ($item->pivot->deleted_at) {
                return $item->pivot->deleted_at;
            } elseif ($item->trashed()) {
                return $item->deleted_at;
            }
            return $item->pivot->updated_at;
        }
    }
}
