<?php

namespace Sneefr\Listeners\MessageWasPosted;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sneefr\Events\MessageWasPosted;

class EmailMessage implements ShouldQueue
{
    /**
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    private $mailer;

    /**
     * Create the event listener.
     *
     * @param \Illuminate\Contracts\Mail\Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  MessageWasPosted $event
     *
     * @return void
     */
    public function handle(MessageWasPosted $event)
    {
        $recipient = $event->message->ad->seller;

        $data = [
            'ad'      => $event->message->ad,
            'sender'  => $event->sender,
            'body' => $event->message->body,
        ];

        $this->mailer->send('emails.message', $data, function ($mail) use ($data, $recipient, $event) {

            $mail->from($event->sender->getEmail(), $event->sender->present()->fullName());

            $mail->to($recipient->getEmail(), $recipient->present()->fullName());

            $mail->subject(trans('mails.message.inbox_title'));
        });

        $event->message->is_sent = true;
        $event->message->save();
    }
}
