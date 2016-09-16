<?php

namespace Sneefr\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */

    protected $policies = [
        \Sneefr\Models\Ad::class   => \Sneefr\Policies\AdPolicy::class,
        \Sneefr\Models\Shop::class   => \Sneefr\Policies\ShopPolicy::class,
        \Sneefr\Models\User::class   => \Sneefr\Policies\UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('show-discussion', function ($user, $discussion) {
            $participantsIds = $discussion->participants->pluck('id')->all();

            return in_array($user->id, $participantsIds);
        });

        // A person needs to own a like in order to delete it.
        Gate::define('destroy-like', function ($user, $like) {
            return $like->user_id === $user->id;
        });
    }
}
