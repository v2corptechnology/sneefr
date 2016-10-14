<?php

namespace Sneefr\Listeners\AdWasPurchased;

use Carbon\Carbon;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Sneefr\Events\AdWasPurchased;
use Sneefr\Price;

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
        $recipient = $event->ad->seller;
        $request = collect($event->request);
        $price = new Price($event->charge->amount);

        // Data passed to the mail
        $data = [
            'ad'           => $event->ad,
            'buyer'        => $event->buyer,
            'quantity'     => $request->get('quantity', 1),
            'price'        => $price->readable2(),
            'evaluateLink' => $this->generateProtectedLink($event),
            'address'      => $this->buildAddress($request),
            'extraInfo'    => $request->get('extra'),
        ];

        $this->mailer->send('emails.sold', $data, function ($mail) use ($data, $recipient) {

            $mail->from(trans('mail.sender_email'), trans('mail.sender_name'));

            $mail->to($recipient->getEmail(), $recipient->present()->fullName());

            $mail->subject(trans('mails.sold.inbox_title'));
        });
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

    /**
     * Generate the protected link to fill the evaluation.
     *
     * @param \Sneefr\Events\AdWasPurchased $event
     *
     * @return string
     */
    protected function generateProtectedLink(AdWasPurchased $event) : string
    {
        $linkInfo = $this->encrypter->encrypt([
            'ad_id'      => $event->ad->getId(),
            'expires_at' => Carbon::now()->addDays(10),
        ]);

        return route('evaluations.create', ['key' => $linkInfo]);
    }
}
