<?php

namespace Sneefr\Jobs;

use Twilio;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSms extends Job implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var string
     */
    private $message;

    /**
     * @var
     */
    private $phoneNumber;

    /**
     * SendSms constructor.
     *
     * @param        $phoneNumber
     * @param string $message
     */
    public function __construct($phoneNumber, string $message)
    {
        $this->message = $message;
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->guardAgainstinvalidSms();

        try {
            Twilio::message($this->phoneNumber, $this->message);
        } catch (\Exception $e) {
            \Log::error('Cannot send verification code', [
                'phone_number' => $this->phoneNumber,
                'message'      => $this->message,
                'Exception'    => $e->getMessage(),
            ]);
        }
    }

    /**
     * Check all parameters are ok to send the SMS.
     *
     * @throws InvalidArgumentException
     */
    private function guardAgainstinvalidSms()
    {
        if (! $this->phoneNumber) {
            throw new InvalidArgumentException("Specify phone number before sending a SMS");
        }

        if (! $this->message) {
            throw new InvalidArgumentException("Specify text before sending a SMS");
        }
    }
}
