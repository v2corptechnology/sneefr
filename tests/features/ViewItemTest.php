<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewItemTest extends TestCase
{
    use DatabaseMigrations;

    public function test_view_stock()
    {
        $ad = factory(\Sneefr\Models\Ad::class)->create(['remaining_quantity' => 30]);

        $this->visit(route('items.show', $ad))
            ->see( trans_choice('ad.show.stock', 30, ['nb' => 30]) );
    }

    public function test_asks_for_login_before_buy()
    {
        $ad = factory(\Sneefr\Models\Ad::class)->create();

        $this->visit(route('items.show', $ad))
            ->see('href="#LoginBefore"')
            ->see('id="LoginBefore"');
    }

    public function test_buy_is_active_when_login()
    {
        $ad = factory(\Sneefr\Models\Ad::class)->create();
        $user = factory(\Sneefr\Models\User::class)->create();

        $this->actingAs($user);
        
        $this->visit(route('items.show', $ad))
            ->see(route('payments.create', ['ad' => $ad]))
            ->dontSee('href="#LoginBefore"');
    }
}
