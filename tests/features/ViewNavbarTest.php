<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewNavbarTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(\Sneefr\Models\User::class)->create();
        $shop = factory(\Sneefr\Models\Shop::class)->create();
        $ads = factory(\Sneefr\Models\Ad::class, 3)->create();
        $shop->ads()->saveMany($ads);
    }

    public function test_presence_of_dropdown_when_not_connected()
    {
        $this->visit('/')->dontSee('role="dropdown-menu"');
    }

    public function test_presence_of_dropdown_when_connected()
    {
        $this->actingAs($this->user);
        $this->visit('/')->see('role="dropdown-menu"');
    }

    public function test_can_not_view_nav_avatar_when_not_connected(){
        $this->visit('/')->dontSee('role="card__avatar"');
    }

    public function test_presence_of_user_avatar()
    {
        $this->actingAs($this->user);
        $this->visit('/')
             ->see('role="card__avatar"')
             ->see($this->user->present()->fullName());
    }

    public function test_presence_of_shop_avatar()
    {
        $this->actingAs($this->user);
        $shop = factory(\Sneefr\Models\Shop::class)->make();
        $this->user->shop()->save($shop);
        $this->visit('/')
             ->see('role="card__avatar"')
             ->see('src="' . $shop->getLogo('25x25') . '"')
             ->see('alt="' . $shop->getName() . '"');
    }



}