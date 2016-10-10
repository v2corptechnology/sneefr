<?php

namespace Sneefr\Repositories\Category;

interface CategoryRepository
{
    /**
     * Get nested array of root/sub categories
     *
     * @return array
     */
    public function getCategoriesTree();

    /**
     * Get flat array of all categories
     *
     * @return array
     */
    public function getCategories();
}