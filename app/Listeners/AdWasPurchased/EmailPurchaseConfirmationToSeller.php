<?php

namespace Sneefr\Listeners\AdWasPurchased;

use Carbon\Carbon;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Sneefr\Events\AdWasPurchased;

class EmailPurchaseConfirmationToSeller implements ShouldQueue
{
    /**
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    private $encrypter;

    /**
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    private $mailer;

    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $config;

    /**
     * Create the event listener.
     *
     * @param \Illuminate\Contracts\Mail\Mailer          $mailer
     * @param \Illuminate\Contracts\Encryption\Encrypter $encrypter
     * @param \Illuminate\Contracts\Config\Repository    $config
     */
    public function __construct(Mailer $mailer, Encrypter $encrypter, Repository $config)
    {
        $this->encrypter = $encrypter;
        $this->mailer = $mailer;
        $this->config = $config;
    }

    /**
     * Handle the event.
     *
     * @param \Sneefr\Events\AdWasPurchased $event
     */
    public function handle(AdWasPurchased $event)
    {
        // For simplicity
        $request = collect($event->request);
        $seller = $event->ad->seller;
        $ad = $event->ad;

        // Check we can send emails to the user
        if (! $seller->getEmail()) {
            return;
        }

        $linkInfo = $this->encrypter->encrypt([
            'ad_id'      => $event->ad->getId(),
            'expires_at' => Carbon::now()->addDays(10),
        ]);

        // Runtime-change the locale of the application.
        $this->config->set('app.locale', $seller->getLanguage());

        // Data used by the view
        $data = [
            'ad'           => $ad,
            'seller'       => $seller,
            'buyer'        => $event->buyer,
            'extraInfo'    => $request->get('extra'),
            'address'      => $this->buildAddress($request),
            'recipient'    => $seller,
        ];

        $callback = function ($message) use ($seller, $data) {
            $message
                ->from(trans('mail.sender_email'), trans('mail.sender_name'))
                ->to($seller->getEmail(), $seller->present()->fullName())
                ->subject(trans('mail.deal_finished_seller.title'));
        };

        $this->mailer->send('emails.deal_finished_seller', $data, $callback);
    }

    /**
     * @param \Illuminate\Support\Collection $request
     *
     * @return string
     */
    private function buildAddress(Collection $request) : string
    {
        return $request->get('shipping_name') .
        '<br>' . PHP_EOL .
        $request->get('shipping_address_line1') .
        '<br>' . PHP_EOL .
        $request->get('shipping_address_zip') . ' ' . $request->get('shipping_address_city') .
        '<br>' . PHP_EOL .
        $request->get('shipping_address_country');

        //$request->get('shipping_address_state')
        //$request->get('shipping_address_country_code')
    }
}
