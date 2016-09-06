<?php namespace Sneefr\Jobs;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use Sneefr\Models\User;

class VerifyEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var \Sneefr\Jobs\User
     */
    private $user;

    /**
     * Create a new job instance.
     *
     * @param \Sneefr\Models\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @param \Illuminate\Contracts\Bus\Dispatcher $dispatcher
     * @param \Illuminate\Config\Repository        $config
     */
    public function handle(Dispatcher $dispatcher, Repository $config)
    {
        // Set the email as unverified
        $this->user->email_verified = false;
        $this->user->save();

        // Runtime-change the locale of the application.
        $config->set('app.locale', $this->user->getLanguage());

        // Send the validation email
        $this->sendVerificationEmail($this->user);

        // Update the rank of the user
        $dispatcher->dispatch(new UpdateRank($this->user));
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
