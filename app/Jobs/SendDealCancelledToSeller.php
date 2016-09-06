<?php namespace Sneefr\Jobs;

use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sneefr\Models\Ad;

class SendDealCancelledToSeller extends Job implements ShouldQueue
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
        $seller = $this->ad->seller;
        $buyer = $this->ad->buyer;
        $ad = $this->ad;

        // Check we can send emails to the user
        if (!$seller->isEmailable) {
            return;
        }

        // Runtime-change the locale of the application.
        $config->set('app.locale', $seller->getLanguage());

        // Data used by the view
        $data = [
            'ad'           => $ad,
            'seller'       => $seller,
            'buyer'        => $buyer,
            'receiverHash' => $seller->getRouteKey(),
        ];

        $callback = function ($message) use ($seller, $data) {
            $message
                ->from(trans('mail.sender_email'), trans('mail.sender_name'))
                ->to($seller->getEmail(), $seller->present()->fullName())
                ->subject(trans('mail.deal_refused_seller.title'));
        };

        $mailer->send('emails.deal_refused_seller', $data, $callback);
    }
}
