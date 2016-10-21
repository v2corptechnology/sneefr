<?php namespace Sneefr;

use Carbon\Carbon;
use Sneefr\Models\User;

class UserPayment
{
    /**
     * @var \Sneefr\Models\User
     */
    protected $user;

    /**
     * UserPayment constructor.
     *
     * @param \Sneefr\Models\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Check if the user have a payment method.
     *
     * @return bool
     */
    public function hasOne() : bool
    {
        return ! is_null($this->user->payment);
    }
}
