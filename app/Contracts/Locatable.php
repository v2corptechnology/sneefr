<?php

namespace Sneefr\Contracts;

/**
 * Allows something to be located via geographic coordinates.
 */
interface Locatable
{
    /**
     * Get the latitude of the thing’s location.
     *
     * @return float|null
     */
    public function latitude();

    /**
     * Get the longitude of the thing’s location.
     *
     * @return float|null
     */
    public function longitude();

    /**
     * Check if the thing has enough defined coordinates to be located.
     *
     * @return bool  True if all the necessary coordinates are set.
     */
    public function isLocatable();
}
