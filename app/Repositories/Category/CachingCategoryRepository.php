<?php

namespace Sneefr\Repositories\Category;

use Illuminate\Contracts\Cache\Repository as Cache;

class CachingCategoryRepository implements CategoryRepository
{
    /**
     * @var EloquentCategoryRepository
     */
    private $repository;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @param EloquentCategoryRepository $repository
     * @param Cache $cache
     */
    public function __construct(EloquentCategoryRepository $repository, Cache $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * Get nested array of root/sub categories
     *
     * @return array
     */
    public function getCategoriesTree()
    {
        return $this->cache->rememberForever('categories_tree', function () {
            return $this->repository->getCategoriesTree();
        });
    }

    /**
     * Get flat array of all categories
     *
     * @return array
     */
    public function getCategories()
    {
        return $this->cache->rememberForever('categories', function () {
            return $this->repository->getCategories();
        });
    }
}