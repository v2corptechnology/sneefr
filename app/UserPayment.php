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

    /**
     * Check if we ask for a payment method.
     *
     * @return bool
     */
    public function isAsked() : bool
    {
        $isOldEnough = Carbon::parse($this->user->created_at)->diffInDays() > 15;

        return (!$this->hasOne() && $isOldEnough);
    }
}
