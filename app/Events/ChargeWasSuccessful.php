<?php namespace Sneefr\Events;

use Sneefr\Models\Ad;
use Sneefr\Models\User;
use Stripe\Charge;

class ChargeWasSuccessful extends Event
{
    /**
     * @var \Sneefr\Models\Ad
     */
    public $ad;

    /**
     * @var \Sneefr\Models\User
     */
    public $buyer;

    /**
     * @var \Stripe\Charge
     */
    public $charge;

    /**
     * @var array
     */
    public $request;

    /**
     * Create a new event instance.
     *
     * @param \Stripe\Charge      $charge
     * @param \Sneefr\Models\Ad   $ad
     * @param \Sneefr\Models\User $buyer
     * @param array               $request
     */
    public function __construct(Charge $charge, Ad $ad, User $buyer, array $request)
    {
        $this->charge = $charge;
        $this->ad = $ad;
        $this->buyer = $buyer;
        $this->request = $request;
    }
}
