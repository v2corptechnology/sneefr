<?php namespace Sneefr\Providers;

use Illuminate\Support\ServiceProvider;
use Sneefr\Services\Gamificator;

/**
 * Provide a singleton-based IoC binding for the Sneefr Gamificator.
 */
class GamificatorServiceProvider extends ServiceProvider
{
    /**
     * Register a singleton of the Gamificator with the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('sneefr.gamificator', function () {
            return new Gamificator();
        });
    }
}
