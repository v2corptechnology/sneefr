<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewNavbarTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        // Homepage needs at least one ad to work
        factory(\Sneefr\Models\Ad::class, 1)->create();
    }

    public function test_cannot_view_avatar_and_menu_when_guest()
    {
        $this->visit('/')
            ->dontSee('<li role="presentation" class="navbar__avatar">')
            ->dontSee('<li role="presentation"><a class="card__avatar"');
    }

    public function test_view_avatar_and_menu_when_auth()
    {
        $this->actingAs(factory(\Sneefr\Models\User::class)->create());

        $this->visit('/')
            ->see('<a class="navbar__profile dropdown-toggle" data-toggle="dropdown"')
            ->see('<li role="presentation" class="navbar__avatar">');
    }

    public function test_view_users_avatar_when_having_no_shop()
    {
        $user = factory(\Sneefr\Models\User::class)->create();

        $this->actingAs($user);

        $this->visit('/')
            ->see($user->facebook_id . '.jpg" alt="'.$user->present()->fullName().'"');
    }

    public function test_view_shops_avatar_when_having_a_shop()
    {
        $user = factory(\Sneefr\Models\User::class)->create();
        $shop = factory(\Sneefr\Models\Shop::class)->make();
        $user->shop()->save($shop);

        $this->actingAs($user);

        $this->visit('/')
            ->see('<img class="card__image" src="'.$shop->getLogo('25x25').'" alt="'.$shop->getName().'"');
    }
}
