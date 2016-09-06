<?php namespace Sneefr\Providers;

use Illuminate\Support\ServiceProvider;
use Sneefr\Composers\NavComposer;
use Sneefr\Composers\NotificationComposer;

class ComposerServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->view->composer(['layouts.master'], NotificationComposer::class);
    }

}
