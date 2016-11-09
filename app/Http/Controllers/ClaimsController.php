<?php

namespace Sneefr\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Sneefr\Models\Claim;
use Sneefr\Models\Shop;
use Sneefr\Notifications\ClaimApproved;
use Sneefr\Notifications\ClaimRejected;

class ClaimsController extends Controller
{
    public function index()
    {
        $claims = Claim::latest()->with('user', 'shop')->get();

        return view('admin.claims.index', compact('claims'));
    }

    public function store(Request $request)
    {
        $shop = Shop::findOrFail($request->input('shop_id'));

        if ($shop->isClaimedBy(auth()->id()) || auth()->user()->claims->count() > 0) {
            return redirect()
                ->route('shops.show', $shop)
                ->with('error', 'You already have a claim waiting for validation, try later with this one.');
        }

        Claim::create(['user_id' => auth()->id(), 'shop_id' => $shop->getId()]);

        return redirect()
            ->route('shops.show', $shop)
            ->with('success', 'Your claim is pending and a moderator will contact you in less than 24 hours.');
    }

    /**
     * The claim is approved, attribute the shop to the user.
     *
     * @param \Sneefr\Models\Claim $claim
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Claim $claim)
    {
        // Attach the shop to the claimer user
        $claim->shop->update(['user_id' => $claim->user->getId()]);

        $claim->delete();

        Notification::send($claim->user, new ClaimApproved($claim));

        return redirect()->route('claims.index');
    }

    /**
     * The claim is rejected, forget about it.
     *
     * @param \Sneefr\Models\Claim $claim
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Claim $claim)
    {
        $claim->delete();

        Notification::send($claim->user, new ClaimRejected($claim));

        return redirect()->route('claims.index');
    }
}
