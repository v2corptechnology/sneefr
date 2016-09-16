<?php namespace Sneefr\Listeners\AdWasPurchased;

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
        // Runtime-change the locale of the application.
        $this->config->set('app.locale', $event->buyer->getLanguage());

        $vendorName = $event->ad->isInShop()
            ? $event->ad->shop->getName()
            : $event->ad->seller->present()->givenName();

        // Data passed to the mail
        $data = [
            'vendorName'   => $vendorName,
            'evaluateLink' => $this->generateProtectedLink($event),
            'recipient'    => $event->buyer,
        ];

        $this->mailer->send('emails.waiting-evaluation', $data, function ($mail) use ($data) {

            $mail->from(trans('mail.sender_email'), trans('mail.sender_name'));

            $mail->to($data['recipient']->getEmail(), $data['recipient']->present()->fullName());

            $mail->subject(trans('mail.waiting-evaluation.title', ['vendorName' => $data['vendorName']]));
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
