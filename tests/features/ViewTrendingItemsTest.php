<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewTrendingItemsTest extends TestCase
{
    use DatabaseMigrations;

    /*public function test_can_view_trending_shops()
    {
        $shop = factory(\Sneefr\Models\Shop::class)->make(['data' => ['name' => 'Trending test shop']]);

        $this->visit('/')
             ->see('Trending test shop');
    }*/

    public function test_can_view_total_ads_in_shop()
    {
        $user = factory(\Sneefr\Models\User::class)->create();

        dd(app()->environment());

        dd($user->id);
        $shop = factory(\Sneefr\Models\Shop::class)->make(['data' => ['name' => 'Trending shop']]);
        $ads = factory(\Sneefr\Models\Ad::class, 3)->create();

        $shop->ads()->saveMany($ads);

        $this->visit('/')
            ->see('3 ads');
    }
}
