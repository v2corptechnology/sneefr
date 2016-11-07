<?php

namespace Sneefr\Listeners\AdWasPurchased;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Sneefr\Events\AdWasPurchased;
use Sneefr\Price;

class EmailPurchaseConfirmationToSeller implements ShouldQueue
{
    /**
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    private $mailer;

    /**
     * Create the event listener.
     *
     * @param \Illuminate\Contracts\Mail\Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
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
            'ad'        => $event->ad,
            'buyer'     => $event->buyer,
            'quantity'  => $request->get('quantity', 1),
            'price'     => $price->formatted(),
            'address'   => $this->buildAddress($request),
            'extraInfo' => $request->get('extra'),
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
}
