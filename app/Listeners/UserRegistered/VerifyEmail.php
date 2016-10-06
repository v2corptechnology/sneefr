<?php

namespace Sneefr\Listeners\UserRegistered;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use Sneefr\Events\UserRegistered;
use Sneefr\Models\User;

class VerifyEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event, Repository $config)
    {
        if($event->user->email_verified == false){
            // Runtime-change the locale of the application.
            $config->set('app.locale', $event->user->getLanguage());

            // Send the validation email
            $this->sendVerificationEmail($event->user);
        }
    }

    /**
     * Send the verification email.
     *
     * @param \Sneefr\Models\User $user
     */
    private function sendVerificationEmail(User $user)
    {
        if (! $user->getEmail()) {
            return;
        }

        // Generate a key used to identify the user through the sent link
        $key = encrypt(['id' => $user->getId(), 'email' => $user->getEmail()]);

        // Data we use in the email view
        $data = [
            'name'         => $user->present()->givenName(),
            'key'          => $key,
            'receiver'     => $user,
            'receiverHash' => $user->getRouteKey(),
        ];

        $callback = function ($message) use ($user) {
            $message
                ->from(trans('mail.sender_email'), trans('mail.sender_name'))
                ->to($user->getEmail(), $user->present()->fullName())
                ->subject(trans('mail.verify-email.title'));
        };

        try {

            Mail::send('emails.verify-email', $data, $callback);

        } catch (\Exception $e) {

            \Log::error('Cannot send verification E-mail', [
                'email'    => $user->getEmail(),
                'name'    => $user->present()->fullName(),
                'Exception'    => $e->getMessage(),
            ]);

        }
    }
}
