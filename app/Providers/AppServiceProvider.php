<?php

namespace Sneefr\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BillingInterface::class, StripeBilling::class);

        if (config('sneefr.APP_DEBUGBAR')) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }

        if (in_array($this->app->environment(), ['staging', 'production'])) {
            $this->app->register(\Jenssegers\Rollbar\RollbarServiceProvider::class);
        }
    }
}
