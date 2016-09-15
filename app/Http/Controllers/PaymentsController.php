<?php namespace Sneefr\Http\Controllers;

use Illuminate\Http\Request;
use Sneefr\Events\AdWasPurchased;
use Sneefr\Events\ChargeWasSuccessful;
use Sneefr\Http\Requests\BillingRequest;
use Sneefr\Jobs\Notify;
use Sneefr\Jobs\SendDealCancelledToSeller;
use Sneefr\Jobs\SendFinishedDealToBuyer;
use Sneefr\Jobs\SendFinishedDealToSeller;
use Sneefr\Jobs\StoreSuccessfulTransaction;
use Sneefr\Jobs\UpdateRank;
use Sneefr\Models\Ad;
use Sneefr\Services\StripeBilling;

class PaymentsController extends Controller
{
    /**
     * Display the form to confirm a payment.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        // Get the ad
        $ad = Ad::findOrFail($request->get('ad'));

        // Is the current user authorized to buy this ad
        $this->authorize('buy', $ad);

        return view('payments.create', compact('ad'));
    }

    /**
     * Process the transaction.
     *
     * @param \Sneefr\Http\Requests\BillingRequest $request
     * @param \Sneefr\Services\StripeBilling       $billing
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BillingRequest $request, StripeBilling $billing)
    {
        // Get the ad
        $ad = Ad::findOrFail($request->input('ad'));

        // Is the current user authorized to buy this ad
        $this->authorize('buy', $ad);

        // Charge the user
        $charge = $billing->charge($billing->generateChargeDetails($ad, $request));

        // Let's say to everyone this ad was purchased
        event(new AdWasPurchased($ad, auth()->user(), $request->all(), $charge));

        return redirect()->route('home')->with('success', trans('feedback.payment_success_shop'));
    }

    public function connect(Request $request)
    {
        // check csrf match
        // check scope=read_write& or ?error=access_denied&error_description=T

        if ($request->has('code')) { // Redirect w/ code

            // Protect against CSRF
            if (! hash_equals($request->get('state'), csrf_token())) {
                throw new TokenMismatchException;
            }
            
            $token_request_body = array(
                'grant_type' => 'authorization_code',
                'client_id' => config('services.stripe.client_id'),
                'code' => $request->get('code'),
                'client_secret' => config('services.stripe.secret'),
                'redirect_uri'
            );

            $req = curl_init('https://connect.stripe.com/oauth/token');
            curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($req, CURLOPT_POST, true );
            curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));

            // TODO: Additional error handling
            $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
            $resp = json_decode(curl_exec($req), true);
            curl_close($req);

            if (isset($resp['error'])) {
                \Log::emergency('Unable to authorize a stripe account', $resp);
                dd($resp['error_description']);
            }

            auth()->user()->payment = $resp;
            auth()->user()->save();

            $this->dispatch(new UpdateRank(auth()->user()));

            return redirect()->route('profiles.settings.edit', auth()->user())
                ->with('success', 'feedback.payment_connected');
        } elseif ($request->has('error')) { // Error
            \Log::emergency('Unable to connect a stripe account', $request->all());
            dd($request->get('error_description'));
        }
    }


    /**
     * The user refuses the deal.
     *
     * @param int $adId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refuse(int $adId)
    {
        // Get the ad
        $ad = Ad::findOrFail($adId);

        // Is the current user authorized to buy this ad
        $this->authorize('buy', $ad);

        // Unlock the ad
        $ad->unlock();

        // Send a special notification
        $this->dispatch(new Notify($ad, Notify::SPECIAL));

        // Warn the seller the ad is unlocked
        $this->dispatch(new SendDealCancelledToSeller($ad));

        return redirect()->back();
    }
}
