<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ShopOnboardingTest extends TestCase
{
    use DatabaseMigrations;

    public function test_shop_owner_is_asked_to_subscribe()
    {
        $user = factory(\Sneefr\Models\User::class)->create();
        $shop = factory(\Sneefr\Models\Shop::class)->make();
        $user->shop()->save($shop);

        $this->actingAs($user);

        $this->visit(route('shops.show', $shop))
            ->see('subscription-plan');
    }

    public function test_shop_owner_is_asked_to_link_payment_account_once_subscribed()
    {
        $user = factory(\Sneefr\Models\User::class)->create();
        factory(\Laravel\Cashier\Subscription::class)->create(['user_id' => $user['id']]);
        $shop = factory(\Sneefr\Models\Shop::class)->make();
        $user->shop()->save($shop);

        $this->actingAs($user);

        $this->visit(route('shops.show', $shop))
            ->see(trans('shops.show.link_payment_action'));
    }

    public function test_shop_owner_is_asked_to_create_first_ad_when_subscribed_and_payment_linked()
    {
        $user = factory(\Sneefr\Models\User::class, 'with-payment')->create();
        factory(\Laravel\Cashier\Subscription::class)->create(['user_id' => $user['id']]);
        $shop = factory(\Sneefr\Models\Shop::class)->make();
        $user->shop()->save($shop);

        $this->actingAs($user);

        $this->visit(route('shops.show', $shop))
            ->see(trans('shop.ads.btn_create_first_ad'));
    }
}
