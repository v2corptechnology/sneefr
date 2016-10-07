<?php

namespace Sneefr\Jobs;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sneefr\Models\Ad;

class SendExpiredLockEmail extends Job implements ShouldQueue
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
        $buyer = $this->ad->lockedFor;

        if (!$buyer) {
            return;
        }

        $data = [
            'ad'           => $this->ad,
            'user'         => $buyer,
            'receiverHash' => $buyer->getRouteKey(),
        ];

        // Runtime-change the locale of the application.
        $config->set('app.locale', $buyer->getLanguage());

        $callback = function ($message) use ($buyer, $data) {
            $message
                ->from(trans('mail.sender_email'), trans('mail.sender_name'))
                ->to($buyer->getEmail(), $buyer->present()->fullName())
                ->subject(trans('mail.expired_lock_buyer.title', ['title' => $this->ad->getTitle()]));
        };

        $mailer->send('emails.expired_lock_buyer', $data, $callback);
    }
}
