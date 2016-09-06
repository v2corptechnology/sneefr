<?php namespace Sneefr\Providers;

use Illuminate\Support\ServiceProvider;
use Hashids\Hashids;

class HashidsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(['Hashids\Hashids' => 'hashids'], function() {

            $key = config('sneefr.keys.APP_HASH_KEY');

            return new Hashids($key, 5);
        });
    }
}
