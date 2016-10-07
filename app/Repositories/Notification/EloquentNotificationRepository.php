<?php

namespace Sneefr\Repositories\Notification;

use Carbon\Carbon;
use Sneefr\Models\Notification;

class EloquentNotificationRepository implements NotificationRepository
{
    /**
     * Grab latest notifications for a user identifier
     *
     * @param int $userId
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLatest($userId)
    {
        return Notification::where('user_id', $userId)
            ->normal()
            ->latest()
            ->get();
    }

    /**
     * Mark unread notifications as read
     *
     * @param int $userId
     *
     * @return bool
     */
    public function markAllReadFor($userId)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => Carbon::now()]);
    }

    /**
     * Retrieve all unread discussions for a user identifier
     *
     * @param int $userId
     *
     * @return int
     */
    public function countUnreadNotificationsFor($userId)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Persists a notification
     *
     * @param array $input
     *
     * @return \Sneefr\Models\Notification
     */
    public function notify(array $input)
    {
        return Notification::create($input);
    }
}
