<?php namespace Sneefr\Repositories\Search;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Cache\Repository as Cache;

class CachingSearchRepository implements SearchRepository
{
    /**
     * @var \Sneefr\Repositories\Search\EloquentSearchRepository
     */
    private $repository;

    /**
     * @var \Illuminate\Contracts\Cache\Repository
     */
    private $cache;

    /**
     * @var \Illuminate\Auth\Guard
     */
    private $auth;

    /**
     * @param \Sneefr\Repositories\Search\EloquentSearchRepository $repository
     * @param \Illuminate\Contracts\Cache\Repository               $cache
     * @param \Illuminate\Contracts\Auth\Guard                     $auth
     */
    public function __construct(EloquentSearchRepository $repository, Cache $cache, Guard $auth)
    {
        $this->repository = $repository;

        $this->cache = $cache;

        $this->auth = $auth;
    }

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
        $this->cache->forget("{$userId}_searches");

        return $this->repository->create($userId, $body);
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
        if ($this->auth->user()) {
            $userId = $this->auth->user()->getAuthIdentifier();
            $this->cache->forget("{$userId}_searches");
        }

        return $this->repository->delete($searchId);
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
        return $this->cache
            ->rememberForever("{$userId}_searches", function () use ($userId) {
                return $this->repository->getSearchesFor($userId);
            });
    }

    /**
     * Get a list of Search models for those identifiers, friends of
     * this user identifier.
     *
     * Todo: Add cache here
     *
     * @param  int $userId
     * @param array $friendIds
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFriendSearchesFor($userId, array $friendIds)
    {
        return $this->repository->getFriendSearchesFor($userId, $friendIds);
    }

    /**
     * @deprecated
     */
    public function getByIdWithTrashed($id, array $with = [])
    {
        return $this->repository->getFriendSearchesFor($id, $with);
    }
}
