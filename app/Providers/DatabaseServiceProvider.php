<?php

namespace Sneefr\Providers;

use Illuminate\Support\ServiceProvider;
use Sneefr\Models\Ad;
use Sneefr\Models\Shop;
use Sneefr\Models\User;

class DatabaseServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMorphMap();

        $this->app->bind(
            \Sneefr\Repositories\User\UserRepository::class,
            \Sneefr\Repositories\User\EloquentUserRepository::class);

        $this->app->bind(
            \Sneefr\Repositories\Ad\AdRepository::class,
            \Sneefr\Repositories\Ad\EloquentAdRepository::class);


        $this->app->bind(
            \Sneefr\Repositories\Evaluation\EvaluationRepository::class,
            \Sneefr\Repositories\Evaluation\EloquentEvaluationRepository::class);
    }

    /**
     * Register the morph map for polymorphic relations.
     *
     * @return void
     */
    protected function registerMorphMap()
    {
        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
            'ad'         => Ad::class,
            'search'     => Search::class,
            'shop'       => Shop::class,
            'user'       => User::class,
        ]);
    }
}
