<?php namespace Sneefr\Jobs;

use Carbon\Carbon;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Sneefr\Models\Notification;
use Sneefr\Models\User;

class SendWaitingNotificationEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * A mailer implementation.
     *
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    private $mailer;

    /**
     * @var \Illuminate\Config\Repository
     */
    private $config;

    /**
     * Execute the job.
     *
     * @param Mailer                        $mailer
     * @param \Illuminate\Config\Repository $config
     */
    public function handle(Mailer $mailer, Config $config)
    {
        $this->mailer = $mailer;
        $this->config = $config;

        foreach ($this->getUnreadNotifications() as $notification) {

            // User must be notifiable
            if ($this->isNotifiable($notification->first()->user)) {
                $this->notify($notification);
            }

        }
    }

    /**
     * Check if a given user has to be notified.
     *
     * @param  \Sneefr\Models\User $user
     *
     * @return bool
     */
    protected function isNotifiable(User $user)
    {
        // User must have an email and subscribed to daily digest
        return ($user->isEmailable && $user->isSubscribedToDailyDigest);
    }

    /**
     * Notify a given user via e-mail.
     *
     * @param \Illuminate\Support\Collection $notification
     */
    protected function notify(Collection $notification)
    {
        $user = $notification->first()->user;

        $data = [
            'name'         => $user->present()->givenName(),
            'nb'           => $notification->count(),
            'user'         => $user,
            'receiverHash' => $user->getRouteKey(),
        ];

        // Runtime-change the locale of the application.
        $this->config->set('app.locale', $user->getLanguage());

        $callback = function ($message) use ($user, $data) {
            $message
                ->from(trans('mail.sender_email'), trans('mail.sender_name'))
                ->to($user->getEmail(), "{$user->present()->fullName()}")
                ->subject(trans_choice('mail.waiting-notification.title', $data['nb'], $data));
        };

        $this->mailer->send('emails.waiting-notification', $data, $callback);
    }

    /**
     * Get the unread notifications that need a mail.
     *
     * @return Collection
     */
    protected function getUnreadNotifications() : Collection
    {
        $minutesAfter = $this->config->get('sneefr.NOTIFY_UNREAD_NOTIFICATION_AFTER');

        $olderThan = Carbon::now()->subMinutes($minutesAfter);

        return Notification::unread()
            ->normal()
            ->where('created_at', '<', $olderThan)
            ->with('user')
            ->get()
            ->groupBy('user_id');
    }
}

