<?php

use Illuminate\Database\Seeder;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = collect(json_decode(file_get_contents('https://www.yelp.com/developers/documentation/v3/all_category_list/categories.json')));

        $topCategories = $categories->filter(function ($category) {
            return collect($category->parents)->contains('shopping');
        });

        $middleCategories = $categories->filter(function ($category) use ($topCategories) {
            return collect($category->parents)->intersect($topCategories->pluck('alias'))->count();
        });

        $childCategories = $categories->filter(function ($category) use ($middleCategories) {
            return collect($category->parents)->intersect($middleCategories->pluck('alias'))->count();
        });

        $topCategories
            ->merge($middleCategories)
            ->merge($childCategories)
            ->unique('alias')
            ->each(function ($category) {
            \Sneefr\Models\Tag::create([
                'alias'     => $category->alias,
                'title'     => $category->title,
                'yelp_data' => (array) $category,
            ]);
        });
    }
}
