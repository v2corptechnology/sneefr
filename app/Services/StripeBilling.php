<?php

namespace Sneefr\Services;

use Sneefr\Contracts\BillingInterface;
use Sneefr\Http\Requests\BillingRequest;
use Sneefr\Models\Ad;
use Stripe\Charge;
use Stripe\Stripe;

class StripeBilling implements BillingInterface
{
    public function __construct()
    {
        // See your keys here https://dashboard.stripe.com/account/apikeys
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Perform a charge.
     *
     * @param array $data
     *
     * @return \Stripe\Charge
     */
    public function charge(array $data)
    {
        try
        {
            return Charge::create([
                'amount'      => $data['amount'],
                'currency'    => 'usd',
                'description' => $data['description'],
                'source'      => $data['token'],
            ], ['stripe_account' => $data['stripeAccountId']]);
        }

        catch (\Stripe\Error\Card $e)
        {
            // Since it's a decline, \Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
            $err = $body['error'];

            \Log::emergency('Your payment failed', ['error' => $err, 'user' => auth()->user(), 'type' => get_class($e)]);
            abort(500, "Something wrong happened : {$err['message']}<br> Don't panic: our team is on it and will reach you");
        }

        catch (\Stripe\Error\RateLimit $e)
        {
            // Too many requests made to the API too quickly
            $body = $e->getJsonBody();
            $err = $body['error'];

            \Log::emergency('Your payment failed', ['error' => $err, 'user' => auth()->user(), 'type' => get_class($e)]);
            abort(500, "Something wrong happened : {$err['message']}<br> Don't panic: our team is on it and will reach you");
        }

        catch (\Stripe\Error\InvalidRequest $e)
        {
            // Invalid parameters were supplied to Stripe's API
            $body = $e->getJsonBody();
            $err = $body['error'];

            \Log::emergency('Your payment failed', ['error' => $err, 'user' => auth()->user(), 'type' => get_class($e)]);
            abort(500, "Something wrong happened : {$err['message']}<br> Don't panic: our team is on it and will reach you");
        }

        catch (\Stripe\Error\Authentication $e)
        {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $body = $e->getJsonBody();
            $err = $body['error'];

            \Log::emergency('Your payment failed', ['error' => $err, 'user' => auth()->user(), 'type' => get_class($e)]);
            abort(500, "Something wrong happened : {$err['message']}<br> Don't panic: our team is on it and will reach you");
        }

        catch (\Stripe\Error\ApiConnection $e)
        {
            // Network communication with Stripe failed
            $body = $e->getJsonBody();
            $err = $body['error'];

            \Log::emergency('Your payment failed', ['error' => $err, 'user' => auth()->user(), 'type' => get_class($e)]);
            abort(500, "Something wrong happened : {$err['message']}<br> Don't panic: our team is on it and will reach you");
        }

        catch (\Stripe\Error\Base $e)
        {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $body = $e->getJsonBody();
            $err = $body['error'];

            \Log::emergency('Your payment failed', ['error' => $err, 'user' => auth()->user(), 'type' => get_class($e)]);
            abort(500, "Something wrong happened : {$err['message']}<br> Don't panic: our team is on it and will reach you");
        }

        catch (Exception $e)
        {
            // Something else happened, completely unrelated to Stripe
            \Log::emergency('Your payment failed', ['error' => $e, 'user' => auth()->user(), 'type' => get_class($e)]);
            abort(500, "Something wrong happened : {$e->get}<br> Don't panic: our team is on it and will reach you");
        };
    }

    /**
     * Generate the URL to ask to connect an account.
     *
     * @return string
     */
    public function getAuthorizeUrl() : string
    {
        $user = auth()->user();

        $authorize_request_body = [
            'response_type' => 'code',
            'scope'         => 'read_write',
            'client_id'     => config('services.stripe.client_id'),
            'state'         => csrf_token(),
            'redirect_uri'  => route('payments.connect'),
            'stripe_user'   => [
                'email'               => $user->getEmail(),
                'url'                 => route('me.show', $user),
                'country'             => 'usa',
                'phone'               => $user->phone->getNumber(),
                'business_name'       => 'sneefR Seller',
                'business_type'       => 'sole_prop',
                'first_name'          => $user->present()->givenName(),
                'last_name'           => $user->present()->surname(),
//                'dob_day'             => $user->birthdate->day,
//                'dob_month'           => $user->birthdate->month,
//                'dob_year'            => $user->birthdate->year,
                'street_address'      => $user->getLocation(),
                'physical_product'    => true,
                'product_description' => 'sneefR online shop',
                'currency'            => 'usd',
            ],
        ];

        return 'https://connect.stripe.com/oauth/authorize' . '?' . http_build_query($authorize_request_body);
    }

    /**
     * Generate the charge object used by the interface to charge for something.
     *
     * @param \Sneefr\Models\Ad                    $ad
     * @param \Sneefr\Http\Requests\BillingRequest $request
     *
     * @return array
     * @throws \Laracodes\Presenter\Exceptions\PresenterException
     */
    public function generateChargeDetails(Ad $ad, BillingRequest $request)
    {
        $quantity = $request->input('quantity', 1);
        $deliveryCost = $ad->delivery->amountFor($request->input('delivery'));

        return [
            'amount'          => $ad->price()->for($quantity)->fee($deliveryCost)->cents(),
            'description'     => 'SneefR : ' . $ad->present()->title() . '(ID: ' . $ad->getId() . ')',
            'token'           => $request->input('stripeToken'),
            'stripeAccountId' => $ad->seller->payment['stripe_user_id'],
        ];
    }
}
