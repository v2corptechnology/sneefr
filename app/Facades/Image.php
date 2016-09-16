<?php namespace Sneefr\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * A facade for the Image service.
 *
 * @see \Sneefr\Services\Image  The class behind this facade
 */
class Image extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @see \Sneefr\Providers\ImageServiceProvider  The service provider for this IoC binding
     */
    protected static function getFacadeAccessor()
    {
        return 'sneefr.image';
    }
}
