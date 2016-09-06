<?php namespace Sneefr\Repositories\Notification;

use Illuminate\Contracts\Cache\Repository as Cache;

class CachingNotificationRepository implements NotificationRepository
{
    /**
     * @var \Sneefr\Repositories\Notification\EloquentNotificationRepository
     */
    private $repository;

    /**
     * @var \Illuminate\Contracts\Cache\Repository
     */
    private $cache;

    /**
     * @param \Sneefr\Repositories\Notification\EloquentNotificationRepository $repository
     * @param \Illuminate\Contracts\Cache\Repository $cache
     */
    public function __construct(EloquentNotificationRepository $repository, Cache $cache)
    {
        $this->repository = $repository;

        $this->cache = $cache;
    }

    /**
     * Grab latest notifications for a user identifier
     *
     * @param int $userId
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLatest($userId)
    {
        return $this->repository->getLatest($userId);
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
        $this->cache->forget("{$userId}_notifications");
        $this->cache->forget("{$userId}_unread_notifications");

        return $this->repository->markAllReadFor($userId);
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
        return $this->repository->countUnreadNotificationsFor($userId);
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
        $userId = $input['user_id'];

        $this->cache->forget("{$userId}_notifications");
        $this->cache->forget("{$userId}_unread_notifications");

        return $this->repository->notify($input);
    }
}
