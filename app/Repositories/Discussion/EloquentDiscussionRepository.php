<?php namespace Sneefr\Repositories\Discussion;

use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;
use Sneefr\Models\DiscussedAd;
use Sneefr\Models\Discussion;
use Sneefr\Models\DiscussionUser;
use Sneefr\Models\Message;
use Sneefr\Models\Shop;

class EloquentDiscussionRepository implements DiscussionRepository
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
    public function get(int $discussionId)
    {
        return Discussion::findOrFail($discussionId);
    }

    /**
     * Get a discussion between two users.
     *
     * @param int $userAId The sender identifier.
     * @param int $userBId The receiver identifier.
     *
     * @return \Sneefr\Models\Discussion|null
     *
     * @throws InvalidArgumentException
     */
    public function between(...$userIds)
    {
        $this->guardAgainstInvalidIds(...$userIds);

        // We start by getting all the discussions that
        // involve at least one of the specified users.
        $discussions = DiscussionUser::where('user_id', $userIds[0])
            ->union(DiscussionUser::where('user_id', $userIds[1]))
            ->get();

        // We will then keep the only discussion for which we
        // have as much matching users as the number of
        // users that have been passed as arguments.
        $discussionUsers = $discussions
            // Group the rows by discussion ID in order to see
            // how many users match for each of them.
            ->groupBy('discussion_id')
            // Reject those that do not have enough matches.
            ->reject(function($rows) use ($userIds) {
                return count($rows) != count($userIds);
            })
            // Finally, grab the first group (the only remaining one) and
            // the first line in that group.
            ->first();

        if ($discussionUsers) {
            // In the end, grab the discussion identified by the ID we found.
            return Discussion::where('id', $discussionUsers->first()->discussion_id)->first();
        }
    }

    /**
     * Get all the discussions of a user.
     *
     * @param mixed $userIds The user identifier we want the discussions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * @throws InvalidArgumentException
     */
    public function of($userIds)
    {
        // Always use an array notation
        $userIds = (array) $userIds;

        $this->guardAgainstInvalidIds(...$userIds);

        $discussionIds = DiscussionUser::distinct()
            ->whereIn('user_id', $userIds)
            ->get(['discussion_id'])
            ->pluck('discussion_id');

        return Discussion::with('participants')
            ->orderBy('updated_at', 'desc')
            ->whereIn('id', $discussionIds)
            ->with('likes')
            ->get();
    }

    /**
     * Start or retrieve a discussion between two users.
     *
     * @param int $userAId The first user taking part to the discussion.
     * @param int $userBId The second user taking part to the discussion.
     *
     * @return \Sneefr\Models\Discussion
     *
     * @throws InvalidArgumentException
     */
    public function start($userAId, $userBId)
    {
        $discussion = $this->between($userAId, $userBId);

        if (!$discussion || !$discussion->exists()) {
            $discussion = Discussion::create();

            $discussion->participants()->attach([$userAId, $userBId]);
        }

        return $discussion;
    }

    /**
     * Start a discussion with a shop.
     *
     * @param int                 $userId The user creating the discussion with the shop.
     * @param \Sneefr\Models\Shop $shop   The shop we create the discussion with.
     *
     * @return \Sneefr\Models\Discussion
     */
    public function startWithShop(int $userId, Shop $shop) : Discussion
    {
        $participatingDiscussionIds = \Sneefr\Models\DiscussionUser::where('user_id', $userId)
            ->lists('discussion_id')
            ->all();

        // Todo: use the relationship
        $discussion = \Sneefr\Models\Discussion::whereIn('id', $participatingDiscussionIds)
            ->where('shop_id', $shop->getId())
            ->first();

        // If the discussion already exists, return it
        if ($discussion && $discussion->exists()) {
            return $discussion;
        }
        
        // Otherwise, create the discussion
        $discussion = Discussion::create(['shop_id' => $shop->getId()]);

        $discussion->participants()->attach([$userId, $shop->owner->getId()]);

        return $discussion;
    }

    /**
     * Grab all the discussions with this ad in it.
     *
     * @param int $adId The ad identifier we are looking for.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function discussingAd(int $adId) : Collection
    {
        $discussionIds = DiscussedAd::where('ad_id', $adId)
            ->pluck('discussion_id');

        return Discussion::with('participants')
            ->orderBy('updated_at', 'desc')
            ->whereIn('id', $discussionIds)
            ->get();
    }

    /**
     * Check if we can work with those ids.
     *
     * @param int $id, ... The ids we need to check
     *
     * @return bool
     */
    protected function guardAgainstInvalidIds(...$ids)
    {
        $idCount = count($ids);

        // Verify that we only received positive integers.
        foreach ($ids as $id) {
            if (!is_int($id) || $id < 1) {
                throw new InvalidArgumentException("The id [{$id}] is not a valid integer");
            }
        }

        // Verifiy that there is no duplicate among identifiers.
        if ($idCount && (count(array_unique($ids)) !== $idCount)) {
            $mergedIds = implode(', ', $ids);
            throw new InvalidArgumentException("Some of the provided ids [{$mergedIds}] are the same");
        }
    }

    /**
     * Get all the discussions of a shop.
     *
     * @param int $shopId The shop identifier we want the discussions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function ofShop(int $shopId)
    {
        return Discussion::with('participants')
            ->orderBy('updated_at', 'desc')
            ->where('shop_id', $shopId)
            ->with('likes')
            ->get();
    }
}
