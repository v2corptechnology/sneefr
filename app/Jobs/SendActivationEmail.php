<?php

namespace Sneefr\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Sneefr\Models\User;

class SendActivationEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Dispatcher $dispatcher, Repository $config)
    {
        $this->user->email_verified = false;
        $this->user->save();

        // Runtime-change the locale of the application.
        $config->set('app.locale', $this->user->getLanguage());

        // Send the validation email
        $this->sendActivationEmail($this->user);

        // Update the rank of the user
        $dispatcher->dispatch(new UpdateRank($this->user));
    }

    /**
     * Send the activation email.
     *
     * @param \Sneefr\Models\User $user
     */
    private function sendActivationEmail(User $user)
    {
        if (! $user->getEmail()) {
            return;
        }

        // Generate a key used to identify the user through the sent link
        $key = encrypt(['id' => $user->getId(), 'email' => $user->getEmail()]);

        // Data we use in the email view
        $data = [
            'key'          => $key,
            'receiver'     => $user,
            'receiverHash' => $user->getRouteKey(),
        ];

        $callback = function ($message) use ($user) {
            $message
                ->from(trans('mail.sender_email'), trans('mail.sender_name'))
                ->to($user->getEmail())
                ->subject(trans('mail.activation-email.title'));
        };

        try {

            Mail::send('emails.activation_email', $data, $callback);

        } catch (\Exception $e) {

            \Log::error('Cannot send verification E-mail', [
                'email'    => $user->getEmail(),
                'name'    => $user->present()->fullName(),
                'Exception'    => $e->getMessage(),
            ]);

        }
    }
}
