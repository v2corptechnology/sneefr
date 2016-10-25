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
        $categories = file_get_contents('https://www.yelp.com/developers/documentation/v3/all_category_list/categories.json');

        collect(json_decode($categories))->filter(function ($category) {
            return collect($category->parents)->contains('shopping');
        })->each(function ($category) {
            \Sneefr\Models\Tag::create([
                'alias'     => $category->alias,
                'title'     => $category->title,
                'yelp_data' => (array) $category,
            ]);
        });
    }
}
