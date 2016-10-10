<?php

namespace Sneefr\Events;

use Sneefr\Models\Ad;
use Sneefr\Models\User;
use Stripe\Charge;

class AdWasPurchased extends Event
{
    /**
     * @var \Sneefr\Models\Ad
     */
    public $ad;

    /**
     * @var \Sneefr\Http\Requests\BillingRequest
     */
    public $request;

    /**
     * @var \Sneefr\Models\User
     */
    public $buyer;

    /**
     * @var \Stripe\Charge
     */
    public $charge;

    /**
     * Create a new event instance.
     *
     * @param \Sneefr\Models\Ad                          $ad
     * @param \Sneefr\Models\User                        $buyer
     * @param array|\Sneefr\Http\Requests\BillingRequest $request
     * @param \Stripe\Charge                             $charge
     */
    public function __construct(Ad $ad, User $buyer, array $request, Charge $charge = null)
    {
        $this->ad = $ad;
        $this->buyer = $buyer;
        $this->request = $request;
        $this->charge = $charge;
    }
}
