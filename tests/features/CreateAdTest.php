<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateAdTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(\Sneefr\Models\User::class, 'with-payment')->create();
        factory(\Laravel\Cashier\Subscription::class)->create(['user_id' => $this->user->id]);
        factory(\Sneefr\Models\Category::class, 1)->create();
        factory(\Sneefr\Models\Category::class, 10)->create(['child_of' => 1]);
        $shop = factory(\Sneefr\Models\Shop::class)->make();
        $this->user->shop()->save($shop);
    }

    public function test_can_choose_quantity()
    {
        $this->actingAs($this->user);

        $this->visit(route('items.create'))
            ->see('Quantity');
    }
}
