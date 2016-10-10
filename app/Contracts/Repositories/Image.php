<?php

namespace Sneefr\Contracts\Repositories;

use Sneefr\Repositories\Image\AdImage;

/**
 * Contract that image repositories have to implement.
 */
interface Image
{
    /**
     * Get the images associated with a given ad.
     *
     * @param  int           $adId   Identifier of the ad
     * @param  string|array  $sizes  If set, get only images of that/these size(s)
     *
     * @return array  A multidimensional array of AdImage objects
     */
    public function getForAd($adId, $sizes = null);

    /**
     * Remove an image from the repository.
     *
     * @param  \Sneefr\Repositories\Images\AdImage  $image  The image to remove
     *
     * @return bool  Whether or not the operation succeeded
     */
    public function remove(AdImage $image);

    /**
     * Remove images associated with a given ad.
     *
     * @param  int           $adId   Identifier of the ad
     * @param  string|array  $sizes  If set, remove only images of that/these size(s)
     */
    public function removeForAd($adId, $sizes = null);
}
