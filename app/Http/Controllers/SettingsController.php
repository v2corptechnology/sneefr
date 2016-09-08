<?php namespace Sneefr\Http\Controllers;

use Sneefr\Contracts\BillingInterface;

class SettingsController extends Controller
{
    /**
     * Display settings of this user.
     *
     * @return \Illuminate\View\View
     */
    public function show(BillingInterface $billing)
    {
        return view('me.show', ['authorizeUrl' => $billing->getAuthorizeUrl()]);
    }
}
