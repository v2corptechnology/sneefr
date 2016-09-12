<?php namespace Sneefr\Http\Controllers;

use Sneefr\Contracts\BillingInterface;

class SettingsController extends Controller
{
    /**
     * Display settings of this user.
     *
     * @param \Sneefr\Contracts\BillingInterface $billing
     *
     * @return \Illuminate\View\View
     */
    public function show(BillingInterface $billing)
    {
        return view('me.show');
    }
}
