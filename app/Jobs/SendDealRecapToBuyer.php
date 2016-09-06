<?php namespace Sneefr\Jobs;

use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sneefr\Models\Ad;

class SendDealRecapToBuyer extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var \Sneefr\Jobs\Ad
     */
    protected $ad;

    /**
     * Create a new job instance.
     *
     * @param \Sneefr\Models\Ad $ad
     */
    public function __construct(Ad $ad)
    {
        $this->ad = $ad;
    }

    /**
     * Execute the job.
     *
     * @param \Illuminate\Contracts\Mail\Mailer $mailer
     * @param \Illuminate\Config\Repository     $config
     */
    public function handle(Mailer $mailer, Config $config)
    {
        $buyer = $this->ad->lockedFor;
        $seller = $this->ad->seller;
        $ad = $this->ad;

        // Check we can send emails to the user
        if (!$buyer->isEmailable) {
            return;
        }

        // Runtime-change the locale of the application.
        $config->set('app.locale', $buyer->getLanguage());

        // Data used by the view
        $data = [
            'ad'           => $ad,
            'buyer'        => $buyer,
            'seller'       => $seller,
            'receiverHash' => $buyer->getRouteKey(),
        ];

        $callback = function ($message) use ($buyer, $data) {
            $message
                ->from(trans('mail.sender_email'), trans('mail.sender_name'))
                ->to($buyer->getEmail(), $buyer->present()->fullName())
                ->subject(trans('mail.deal_recap_buyer.title'));
        };

        $mailer->send('emails.deal_recap_buyer', $data, $callback);
    }
}
