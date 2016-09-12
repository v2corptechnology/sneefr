<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewShopPageTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_view_shop()
    {
        $shop = factory(\Sneefr\Models\Shop::class)->create();


        $this->visit('/shops/' . $shop->getRouteKey())
            ->see($shop->getName());
    }

    public function test_can_view_adds_in_shop()
    {
        $shop = factory(\Sneefr\Models\Shop::class)->create();
        $ads = factory(\Sneefr\Models\Ad::class, 3)->create();
        $shop->ads()->saveMany($ads);

        $this->visit('/shops/' . $shop->getRouteKey())
            ->see($shop->getName())
            ->see($ads->first()->getTitle());
    }
}