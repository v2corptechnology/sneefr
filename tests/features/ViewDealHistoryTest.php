<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewDealHistoryTest extends TestCase
{
    use DatabaseMigrations;

    public function test_history_nav_item_is_not_displayed_when_not_connected()
    {
        $this->visit('/')
            ->dontSee('History');
    }

    public function test_history_nav_item_is_displayed_when_connected()
    {
        $user = factory(\Sneefr\Models\User::class)->create();

        $this->actingAs($user);

        $this->visit('/')
            ->see('History');
    }

    public function test_latest_deals_are_displayed()
    {
        $user = factory(\Sneefr\Models\User::class)->create();
        $sale = factory(\Sneefr\Models\Transaction::class)->create(['seller_id' => $user->id]);
        $purchase = factory(\Sneefr\Models\Transaction::class)->create(['buyer_id' => $user->id]);

        $this->actingAs($user);

        $this->visit(route('deals.index'))
            ->see($purchase->ad->present()->title())
            ->see($sale->ad->present()->title());
    }
}
