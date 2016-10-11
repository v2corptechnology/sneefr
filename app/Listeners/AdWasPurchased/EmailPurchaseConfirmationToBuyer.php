<?php

namespace Sneefr\Listeners\AdWasPurchased;

use Carbon\Carbon;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sneefr\Events\AdWasPurchased;

class EmailPurchaseConfirmationToBuyer implements ShouldQueue
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
        $recipient = $event->buyer;

        // Data passed to the mail
        $data = [
            'ad'           => $event->ad,
            'shop'         => $event->ad->shop,
            'evaluateLink' => $this->generateProtectedLink($event),
        ];

        $this->mailer->send('emails.purchased', $data, function ($mail) use ($data, $recipient) {

            $mail->from(trans('mail.sender_email'), trans('mail.sender_name'));

            $mail->to($recipient->getEmail(), $recipient->present()->fullName());

            $mail->subject(trans('mails.purchased.inbox_title'));
        });
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
