<?php

namespace Sneefr\Events;

use Sneefr\Models\Claim;

class ClaimRejected
{
    /**
     * @var \Sneefr\Models\Claim
     */
    public $claim;

    /**
     * Create a new event instance.
     *
     * @param \Sneefr\Models\Claim $claim
     */
    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }
}
