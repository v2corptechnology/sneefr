<?php namespace Sneefr\Jobs;

use Carbon\Carbon;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Sneefr\Models\Ad;

class SendExpiringLocks extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    protected $mailer;

    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Create a new job instance.
     *
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @param \Illuminate\Contracts\Mail\Mailer       $mailer
     * @param \Illuminate\Contracts\Config\Repository $config
     */
    public function handle(Mailer $mailer, Repository $config)
    {
        $this->mailer = $mailer;
        $this->config = $config;

        foreach ($this->getExpiringLocks() as $ad) {
            $this->notify($ad);
        }
    }

    /**
     * Get the locked ads about to expire.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getExpiringLocks() : Collection
    {
        $minutesAfter = $this->config->get('sneefr.NOTIFY_AD_LOCK_AFTER');

        $olderThan = Carbon::now()->subMinutes($minutesAfter);
        // Keep it synced with Sneefr\Console\Kernel's schedule
        $youngerThan = Carbon::now()->subMinutes($minutesAfter + 10);

        return Ad::whereNotNull('locked_for')
            ->where('updated_at', '<=', $olderThan)
            ->where('updated_at', '>', $youngerThan)
            ->with('lockedFor')
            ->get();
    }

    /**
     * Notify the buyer this lock is about to expire.
     * 
     * @param \Sneefr\Models\Ad $ad
     */
    protected function notify(Ad $ad)
    {
        $user = $ad->lockedFor;

        // Check we can send emails to the user
        if (!$user->isEmailable) {
            return;
        }

        $data = [
            'ad'           => $ad,
            'user'         => $user,
            'receiverHash' => $user->getRouteKey(),
        ];

        // Runtime-change the locale of the application.
        $this->config->set('app.locale', $user->getLanguage());

        $callback = function ($message) use ($ad, $user, $data) {
            $message
                ->from(trans('mail.sender_email'), trans('mail.sender_name'))
                ->to($user->getEmail(), $user->present()->fullName())
                ->subject(trans('mail.expiring_lock.title', ['title' => $ad->getTitle()]));
        };

        $this->mailer->send('emails.expiring_lock', $data, $callback);

    }
}
