<?php

namespace Sneefr\Contracts\Entities;

/**
 * Allows a discussed to act as an entity.
 */
interface DiscussedAd
{
    /**
     * Get the discussed ad id.
     *
     * @return int
     */
    public function adId() : int;
}
