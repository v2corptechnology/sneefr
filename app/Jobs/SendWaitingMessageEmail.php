<?php namespace Sneefr\Jobs;

use Carbon\Carbon;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Collection;
use Sneefr\Models\Message;

class SendWaitingMessageEmail extends Job
{
    /**
     * A mailer implementation.
     *
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    private $mailer;

    /**
     * Configuration repository.
     *
     * @var \Illuminate\Config\Repository
     */
    private $config;

    /**
     * Create a new job instance.
     *
     * @param \Illuminate\Contracts\Mail\Mailer $mailer
     * @param \Illuminate\Config\Repository $config
     */
    public function __construct(Mailer $mailer, Config $config)
    {
        $this->mailer = $mailer;
        $this->config = $config;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->getUnreadMessagesByUser()->each(function($unread) {
            // User must have an email
            if ($unread->first()->to->hasVerifiedEmail()) {
                $this->notify($unread);
            }
        });
    }

    /**
     * Notify a given user via e-mail.
     *
     * @param \Illuminate\Support\Collection $unread
     */
    protected function notify(Collection $unread)
    {
        // Shortcuts
        $user = $unread->first()->to;
        $unreadMessagesCount = $unread->count();

        // Runtime-change the locale of the application.
        $this->config->set('app.locale', $user->getLanguage());

        // Data used by the view
        $data = [
            'name'              => $user->givenName,
            'nb'                => $unreadMessagesCount,
            'user'              => $user,
            'firstUnread'       => $unread->first()->body,
            'firstUnreadAuthor' => $unread->first()->from->present()->givenName(),
            'discsussionId'     => $unread->first()->discussion_id,
            'discussion'        => $unread->first()->discussion,
            'receiverHash'      => $user->getRouteKey(),
        ];

        $callback = function ($message) use ($user, $data) {
            $message
                ->from(trans('mail.sender_email'), trans('mail.sender_name'))
                ->to($user->getEmail(), "{$user->surname} {$user->givenName}")
                ->subject(trans_choice('mail.waiting-message.title', $data['nb'], $data));
        };

        $this->mailer->send('emails.waiting-message', $data, $callback);
    }

    /**
     * Get the unread messages that need a notification.
     *
     * @return Collection
     */
    protected function getUnreadMessagesByUser() : Collection
    {
        $minutesAfter = $this->config->get('sneefr.NOTIFY_UNREAD_MESSAGE_AFTER');
        $minutesBefore = $this->config->get('sneefr.NOTIFY_UNREAD_MESSAGE_BEFORE');

        $olderThan = Carbon::now()->subMinutes($minutesAfter);
        $youngerThan = Carbon::now()->subMinutes($minutesBefore);

        return Message::unread()
            ->where('created_at', '>', $youngerThan)
            ->where('created_at', '<', $olderThan)
            ->with('to', 'from', 'discussion')
            ->get()
            ->groupBy('to_user_id');
    }
}

