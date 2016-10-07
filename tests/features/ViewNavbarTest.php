<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewNavbarTest extends TestCase
{
    use DatabaseMigrations;

    public function test_cannot_view_avatar_and_menu_when_guest()
    {
        $this->visit('/')
            ->dontSee('<li class="navbar__avatar"')
            ->dontSee('<li><a class="avatar"');
    }

    public function test_view_logout_when_auth()
    {
        $this->actingAs(factory(\Sneefr\Models\User::class)->create());

        $this->visit('/')
            ->see('<a href="' . route('logout'));
    }

    public function test_show_ad_create_button_for_shop_owner()
    {
        $user = factory(\Sneefr\Models\User::class)->create();
        $shop =  factory(\Sneefr\Models\Shop::class)->create(['user_id' => $user->getId()]);

        $this->actingAs($user);

        $this->visit('/')
            ->see('<a href="'. route('items.create') .'" title="Create an ad">');
    }

    public function test_not_showing_ad_create_button_for_none_shop_owner()
    {
        $this->actingAs(factory(\Sneefr\Models\User::class)->create());

        $this->visit('/')
            ->dontSee('<a href="'. route('items.create') .'" title="Create an ad">');
    }
}
