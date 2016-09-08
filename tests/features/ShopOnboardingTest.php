<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ShopOnboardingTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;

    protected $shop;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(\Sneefr\Models\User::class)->create();
        $this->shop = factory(\Sneefr\Models\Shop::class)->create(['user_id' => $this->user->getId() ]);
    }

    public function test_shop_not_subscribed()
    {
        $this->actingAs($this->user);
        $this->visit('shops/' . $this->shop->getRouteKey())
             ->see('subscription-plan');
    }

    public function test_subscribed_shop()
    {
        $this->actingAs($this->user);
        $this->subscribe($this->user);
        $this->visit('shops/' . $this->shop->getRouteKey())
             ->see(trans('shops.show.link_payment_action'));
    }

    public function test_can_view_btn_create_first_ad()
    {
        $this->actingAs($this->user);
        $this->subscribe($this->user);
        $this->linkStripeAccount($this->user);
        $this->visit('shops/' . $this->shop->getRouteKey())
             ->see(trans('shop.ads.btn_create_first_ad'));
    }

    protected function linkStripeAccount($user)
    {
        $user->stripe_id = 'cus_99L5CJCSCky5Ft';
        $user->card_brand = 'visa';
        $user->card_last_four = '4242';
        $user->payment = '{"scope": "read_write", "livemode": true}';
        $user->save();
    }

    protected function subscribe($user)
    {
        DB::table('subscriptions')->insert(
            [
                'user_id'       => $user->getId(),
                'name'          => 'shop',
                'stripe_id'     => 'sub_8zk4J6ZTYeBaka',
                'stripe_plan'   => 'yearlyadopters',
                'quantity'      => 1,
                'created_at'    => '2016-08-12 18:40:56',
                'updated_at'    => '2016-08-12 18:40:56'
            ]
        );
    }
}