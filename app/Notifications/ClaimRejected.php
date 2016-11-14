<?php

namespace Sneefr\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Sneefr\Models\Claim;

class ClaimRejected extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var \Sneefr\Models\Claim
     */
    private $claim;

    /**
     * Create a new notification instance.
     *
     * @param \Sneefr\Models\Claim $claim
     */
    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The shop claim you made for "'. $this->claim->shop->getName() .'" was rejected.')
            ->line('Contact us if you think this is a wrong decision from our team.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
