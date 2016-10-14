<?php

namespace Sneefr\Jobs;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sneefr\Models\Ad;

class SendUnlockedAdEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * The ad we send the email for.
     *
     * @var \Sneefr\Models\Ad
     */
    private $ad;

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
     * @param \Illuminate\Contracts\Mail\Mailer       $mailer
     * @param \Illuminate\Contracts\Config\Repository $config
     */
    public function handle(Mailer $mailer, Repository $config)
    {
        $seller = $this->ad->user;

        $data = [
            'ad'           => $this->ad,
            'user'         => $seller,
            'receiverHash' => $seller->getRouteKey(),
        ];

        // Runtime-change the locale of the application.
        $config->set('app.locale', $seller->getLanguage());

        $callback = function ($message) use ($seller, $data) {
            $message
                ->from(trans('mail.sender_email'), trans('mail.sender_name'))
                ->to($seller->getEmail(), $seller->present()->fullName())
                ->subject(trans('mail.expired_lock_seller.title', ['title' => $this->ad->getTitle()]));
        };

        $mailer->send('emails.expired_lock_seller', $data, $callback);
    }
}
