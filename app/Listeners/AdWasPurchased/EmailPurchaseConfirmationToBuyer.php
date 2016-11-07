<?php

namespace Sneefr\Listeners\AdWasPurchased;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sneefr\Events\AdWasPurchased;
use Sneefr\Price;

class EmailPurchaseConfirmationToBuyer implements ShouldQueue
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
        $recipient = $event->buyer;
        $request = collect($event->request);
        $price = new Price($event->charge->amount);

        // Data passed to the mail
        $data = [
            'ad'           => $event->ad,
            'shop'         => $event->ad->shop,
            'quantity'     => $request->get('quantity', 1),
            'price'        => $price->formatted(),
            'evaluateLink' => route('evaluations.create', ['ad' => $event->ad->getId()]),
        ];

        $this->mailer->send('emails.purchased', $data, function ($mail) use ($data, $recipient) {

            $mail->from(trans('mail.sender_email'), trans('mail.sender_name'));

            $mail->to($recipient->getEmail(), $recipient->present()->fullName());

            $mail->subject(trans('mails.purchased.inbox_title'));
        });
    }
}
