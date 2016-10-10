<?php

namespace Sneefr\Repositories\Search;

interface SearchRepository
{
    /**
     * Store a new shared search
     *
     * @param int $userId
     * @param string $body
     *
     * @return bool
     */
    public function create($userId, $body);

    /**
     * Remove a shared search
     *
     * @param int $searchId
     *
     * @return bool
     */
    public function delete($searchId);

    /**
     * Get a list of Search models for this identifier.
     *
     * @param  int $userId
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSearchesFor($userId);

    /**
     * Get a list of Search models for those identifiers, friends of
     * this user identifier.
     *
     * @param  int $userId
     * @param array $friendIds
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFriendSearchesFor($userId, array $friendIds);

    /**
     * @deprecated
     */
    public function getByIdWithTrashed($id, array $with = []);
}
