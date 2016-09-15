<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewLoginRegisterTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_register_for_an_account_via_email_with_activation()
    {
        factory(\Sneefr\Models\Ad::class, 3)->create();

        $this->visit('/register')
             ->type('email@sneefr.com', 'email')
             ->type('password', 'password')
             ->type('password', 'password_confirmation')
             ->press(trans('button.register'));

        $this->see(trans('auth.activation'))
            ->seeInDatabase('users', ["email" => "email@sneefr.com", "verified" => 0]);

        $user = \Sneefr\Models\User::whereEmail('email@sneefr.com')->firstOrFail();

        $key = encrypt(['id' => $user->getId(), 'email' => $user->getEmail()]);

        $this->visit("/register/activation/" . $key);

        $this->see(trans('feedback.email_activation_success'))
            ->seeInDatabase('users', ["email" => "email@sneefr.com", "verified" => 1]);
    }

    public function test_login_user_with_not_activated_account()
    {
        $user = factory(\Sneefr\Models\User::class)->create(['verified' => 0, 'password' => bcrypt('password')]);

        $this->visit('/login')
            ->type($user->getEmail(), 'email')
            ->type('password', 'password')
            ->press(trans('button.login'));

        $this->see(trans('feedback.account_not_activated'));
    }


}