<?php

namespace Sneefr\Repositories\Notification;

interface NotificationRepository
{
    /**
     * Grab latest notifications for a user identifier
     *
     * @param int $userId
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLatest($userId);

    /**
     * Mark unread notifications as read
     *
     * @param int $userId
     *
     * @return bool
     */
    public function markAllReadFor($userId);

    /**
     * Retrieve all unread discussions for a user identifier
     *
     * @param int $userId
     *
     * @return int
     */
    public function countUnreadNotificationsFor($userId);

    /**
     * Persists a notification
     *
     * @param array $input
     *
     * @return \Sneefr\Models\Notification
     */
    public function notify(array $input);
}
