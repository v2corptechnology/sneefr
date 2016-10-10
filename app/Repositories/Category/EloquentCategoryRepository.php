<?php

namespace Sneefr\Repositories\Category;

use Sneefr\Models\Category;

class EloquentCategoryRepository implements CategoryRepository
{
    /**
     * Get nested array of root/sub categories
     *
     * @return array
     */
    public function getCategoriesTree()
    {
        // Retrieve categories, parents first
        $categories = Category::orderBy('child_of', 'asc')->get();

        $tree = [];

        foreach ($categories as $category) {
            if ($category->child_of == null) {
                $tree[$category->id] = [];
            } else {
                array_push($tree[$category->child_of], $category->id);
            }
        }

        return $tree;
    }

    /**
     * Get flat array of all categories
     *
     * @return array
     */
    public function getCategories()
    {
        $tree = $this->getCategoriesTree();

        $categories = [];

        foreach ($tree as $root => $subs) {
            $categories[] = ['id' => $root, 'child_of' => null];

            foreach ($subs as $sub) {
                $categories[] = ['id' => $sub, 'child_of' => $root];
            }
        }

        return $categories;
    }
}