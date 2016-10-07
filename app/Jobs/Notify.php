<?php

namespace Sneefr\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Sneefr\Models\Notification;
use Sneefr\Models\User;
use Vinkla\Pusher\Facades\Pusher;

class Notify extends Job implements ShouldQueue
{
    use InteractsWithQueue;

    const SPECIAL = true;

    /**
     * @var mixed The trigger we notify for
     */
    protected $trigger;

    /**
     * @var bool
     */
    protected $isSpecial;

    /**
     * Create a new job instance.
     *
     * @param      $trigger mixed Could be a like, a message, an evaluation, a tag, relationship ...
     * @param bool $isSpecial
     */
    public function __construct($trigger, bool $isSpecial = false)
    {
        $this->trigger = $trigger;
        $this->isSpecial = $isSpecial;
    }

    /**
     * Send and/or save the notification.
     */
    public function handle()
    {
        // If the notification needs to be stored
        if ($this->isPersistent()) {
            $this->saveNotification();
        }

        // Send pushes
        $this->sendPush();
    }

    /**
     * Store the notification
     */
    protected function saveNotification()
    {
        foreach ($this->getUsersToNotify() as $user) {
            // Avoid sending notification to myself
            if ($user->id() != auth()->id()) {
                Notification::create([
                    'user_id'         => $user->id(),
                    'notifiable_type' => get_class($this->trigger),
                    'notifiable_id'   => $this->trigger->id,
                    'is_special'      => $this->isSpecial,
                ]);
            }
        }
    }

    /**
     * Send the push notification
     */
    protected function sendPush()
    {
        foreach ($this->getUsersToNotify() as $user) {
            // Avoid sending notification to myself
            if ($user->id() != auth()->id()) {
                Pusher::trigger('private-' . $user->getRouteKey(), 'new_notification', []);
            }
        }
    }

    /**
     * Check if this event needs to be persisted.
     *
     * @return bool
     */
    protected function isPersistent() : bool
    {
        return true;
    }

    /**
     * Retrieve the concerned users about this notification.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getUsersToNotify() : Collection
    {
        // init $userIds to empty array
        $userIds = [];

        if ($this->trigger instanceof \Sneefr\Models\Like) {
            $userIds = (array) $this->getIdsFromLike($this->trigger->likeable);
        }

        if ($this->trigger instanceof \Sneefr\Models\Tag) {
            $userIds = (array) $this->trigger->user->id();
        }

        if ($this->trigger instanceof \Sneefr\Models\Ad) {
            $ad = $this->trigger;

            // The ad is sold to someone
            if ($ad->lockedFor && $ad->trashed()) {
                $userIds = (array) $ad->seller->id();
            } // The ad is locked for someone
            elseif ($ad->lockedFor && !$ad->trashed()) {
                $userIds = (array) $ad->lockedFor->id();
            } else {
                $userIds = (array) $ad->seller->id();
            }
        }

        return User::whereIn('id', $userIds)->get();
    }

    /**
     * Get the users identifiers concerned by this like
     * TODO: change it to a polymorphic conditional http://zaengle.com/blog/replace-conditional-with-polymorphism
     *
     * @param \Illuminate\Database\Eloquent\Model $liked
     *
     * @return array
     */
    protected function getIdsFromLike(\Illuminate\Database\Eloquent\Model $liked) : array
    {
        switch (get_class($liked)) {

            case \Sneefr\Models\Search::class:
                return (array) $liked->user->id();
                break;

            case \Sneefr\Models\Ad::class:
                return (array) $liked->sellerId();
                break;

            case \Sneefr\Models\Discussion::class:
                return $liked->participants->pluck('id')->all();
                break;

            case \Sneefr\Models\User::class:
                return (array) $liked->getId();
                break;

            default:
                \Log::warning('Nobody will be notified', ['liked' => $liked]);
                return [];
                break;
        }
    }
}
