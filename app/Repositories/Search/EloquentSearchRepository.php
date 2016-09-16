<?php namespace Sneefr\Repositories\Search;

use Sneefr\Models\Search;

class EloquentSearchRepository implements SearchRepository
{
    /**
     * Store a new shared search
     *
     * @param int $userId
     * @param string $body
     *
     * @return bool
     */
    public function create($userId, $body)
    {
        return Search::create([
            'user_id' => $userId,
            'body'    => $body
        ]);
    }

    /**
     * Remove a shared search
     *
     * @param int $searchId
     *
     * @return bool
     */
    public function delete($searchId)
    {
        return Search::destroy($searchId);
    }

    /**
     * Get a list of Search models for those identifiers.
     *
     * @param  int $userId
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSearchesFor($userId)
    {
        return Search::where('user_id', $userId)->get();
    }

    /**
     * Get a list of Search models for those identifiers, friends of
     * this user identifier.
     *
     * @param  int $userId
     * @param array $friendIds
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFriendSearchesFor($userId, array $friendIds)
    {
        array_push($friendIds, $userId);

        return Search::whereIn('user_id', $friendIds)
            ->with(['user', 'likes.user'])
            ->get();
    }

    /**
     * @deprecated
     */
    public function getByIdWithTrashed($id, array $with = [])
    {
        return Search::where('id', $id)->with($with)->withTrashed()->first();
    }
}
