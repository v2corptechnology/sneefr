<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewTrendingItemsTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_view_trending_shops()
    {
        $shop = factory(\Sneefr\Models\Shop::class)->create(['data' => ['name' => 'Trending test shop']]);
        $ads = factory(\Sneefr\Models\Ad::class, 3)->create();
        $shop->ads()->saveMany($ads);

        $this->visit('/')
             ->see('Trending test shop');
    }

    public function test_can_view_trending_users()
    {
        $user = factory(\Sneefr\Models\User::class)->create();
        $shop = factory(\Sneefr\Models\Shop::class)->create(['data' => ['name' => 'Trending test shop']]);
        factory(\Sneefr\Models\Ad::class, 1)->create(['user_id' => $user->id, 'shop_id' => $shop->id]);
        factory(\Sneefr\Models\Ad::class, 2)->create(['user_id' => $user->id]);

        $this->visit('/')
            ->see('2 ads');
    }
}
