<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewTrendingItemsTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_view_trending_shops()
    {
        $shop = factory(\Sneefr\Models\Shop::class)->create(['data' => ['name' => 'Trending test shop']]);
        $ads = factory(\Sneefr\Models\Ad::class, 3)->create();
        $shop->ads()->saveMany($ads);

        $this->visit('/')
            ->see('3 ads')
            ->see('Trending test shop');
    }
}
