<?php

namespace Sneefr\Http\Controllers;

use Sneefr\Http\Requests;
use Sneefr\Http\Requests\BillingRequest;
use Sneefr\Models\Shop;

class SubscriptionsController extends Controller
{
    public function store(BillingRequest $request)
    {
        // Fetch the shop
        $shop = Shop::where('user_id', auth()->id())->withTrashed()->first();

        try {
            auth()->user()
                ->newSubscription('shop', $request->input('plan'))
                ->withCoupon($request->input('coupon'))
                ->create($request->input('stripeToken'));

            // Activate/restore the shop
            $shop->restore();

            session()->flash('success', trans('feedback.subscription_success'));

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }

        return redirect()->route('shops.show', $shop);
    }
}
