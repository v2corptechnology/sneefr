<?php namespace Sneefr\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Register a service class to get images from a third-party platform.
 */
class ImageServiceProvider extends ServiceProvider
{
    /**
     * Register the service class.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(['Sneefr\Services\Image' => 'sneefr.image']);
    }
}
