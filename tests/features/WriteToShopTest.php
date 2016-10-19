<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class WriteToShopTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_see_the_write_button()
    {
        $item = factory(\Sneefr\Models\Ad::class)->create();

        $this->visit(route('items.show', $item))
            ->see('contact');
    }

    public function test_can_write_only_when_connected()
    {
        $user = factory(\Sneefr\Models\User::class)->create();
        $item = factory(\Sneefr\Models\Ad::class)->create();

        $this->visit(route('items.show', $item))
            ->dontSee('name="message-body"');

        $this->actingAs($user);

        $this->visit(route('items.show', $item))
            ->see('name="message-body"');
    }
}
