<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewIntercomHelperTest extends TestCase
{
    use DatabaseMigrations;

    public function test_helper_is_displayed()
    {
        $this->visit(route('home'))
            ->see('<script>window.intercomSettings');
    }

    public function test_helper_is_disabled_for_items()
    {
        $item = factory(\Sneefr\Models\Ad::class)->create();

        $this->visit(route('items.show', $item))
            ->dontSee('<script>window.intercomSettings');
    }
}
