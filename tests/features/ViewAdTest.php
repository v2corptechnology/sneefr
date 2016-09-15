<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewAdTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_view_remaining_stocks()
    {
        $ad = factory(\Sneefr\Models\Ad::class)->create();
        factory(\Sneefr\Models\Stock::class)->create(['ad_id' => $ad->id]);

        $remaining = $ad->stock->remaining;

        $this->visit(route('ad.show', $ad))
            ->see( trans_choice('ad.show.stock', $remaining, ['nb' => $remaining]) );
    }
}
