<?php

namespace Sneefr\Repositories\Image;

/**
 * Value object representing an image related to an ad.
 */
final class AdImage
{
    /**
     * Public, web-accessible absolute URL of the image.
     *
     * @var string
     */
    public $url;

    /**
     * Creates a new instance from an URL.
     *
     * @param string  $url  The absolute URL of the image
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Return the URL of the image when casting it to a string.
     *
     * @return string  The absolute URL of the image
     */
    public function __toString()
    {
        return (string) $this->url;
    }
}
