<?php

namespace Sneefr\Repositories\Discussion;

use Illuminate\Database\Eloquent\Collection;
use Sneefr\Models\Shop;

interface DiscussionRepository
{
    /**
     * Get a discussion according to its identifier.
     *
     * @param int $discussionId The discussion identifier we want to retrieve.
     *
     * @return \Sneefr\Models\Discussion
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function get(int $discussionId);

    /**
     * Get a discussion between two users.
     *
     * @param int $senderId The sender identifier.
     * @param int $recipientId The receiver identifier.
     *
     * @return \Sneefr\Models\Discussion
     */
    public function between(...$userIds);

    /**
     * Get all the discussions of a user.
     *
     * @param mixed $userIds The user identifier we want the discussions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * @throws InvalidArgumentException
     */
    public function of($userIds);

    /**
     * Get all the discussions of a shop.
     *
     * @param int $shopId The shop identifier we want the discussions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function ofShop(int $shopId);

    /**
     * Start or retrieve a discussion between two users.
     *
     * @param int $userAId The first user taking part to the discussion.
     * @param int $userBId The second user taking part to the discussion.
     *
     * @return \Sneefr\Models\Discussion
     */
    public function start($userAId, $userBId);

    /**
     * Start a discussion with a shop.
     *
     * @param int                 $userId The user creating the discussion with the shop.
     * @param \Sneefr\Models\Shop $shop   The shop we create the discussion with.
     *
     * @return \Sneefr\Models\Discussion
     */
    public function startWithShop(int $userId, Shop $shop);

    /**
     * Grab all the discussions with this ad in it.
     *
     * @param int $adId The ad identifier we are looking for.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function discussingAd(int $adId) : Collection;
}
