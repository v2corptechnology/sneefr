<?php namespace Sneefr;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Crypt;
use libphonenumber\NumberParseException;
use Libphonenumber\PhoneNumber as LibPhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Sneefr\Jobs\SendSms;

class PhoneNumber
{
    use DispatchesJobs;

    /** Maximum times a user can send himself the verification code */
    const MAX_VERIFICATION_TRIALS = 3;

    private $phoneData;

    /**
     * UserEvaluations constructor.
     *
     * @param $data
     */
    public function __construct($data)
    {
        if ($data) {
            // Todo: extract each data piece to its own property
            $this->phoneData = json_decode($data);
            $this->phoneData->phone = Crypt::decrypt($this->phoneData->phone);
        }

    }

    public function getNumber() : string
    {
        return $this->phoneData->phone ?? '';
    }

    public function canAskCode() : bool
    {
        if ($this->phoneData) {
            return !$this->phoneData->valid && $this->phoneData->attempt < self::MAX_VERIFICATION_TRIALS;
        }

        return true;
    }

    public function update($phoneNumber)
    {
        // Generate verification code
        $code = mt_rand(1000, 9999);
        // text message to be sent
        $message = trans('profile.parameters.sms_msg', ['code' => $code]);

        $phoneUtil = PhoneNumberUtil::getInstance();

        $phoneNumber = $phoneUtil->parse($phoneNumber, "AUTO");

        $formattedPhoneNumber = $phoneUtil->format($phoneNumber, PhoneNumberFormat::E164);

        $this->dispatch(new SendSms($formattedPhoneNumber, $message));

        $this->saveNewPhoneNumber($phoneNumber, $code);
    }

    public function confirm($confirmCode)
    {
        $confirms = ! $this->phoneData->valid && $this->phoneData->code == $confirmCode;

        if ($confirms) {
            $data = $this->phoneData;
            $data->phone = Crypt::encrypt($data->phone);
            $data->valid = true;

            auth()->user()->phone = json_encode($data);
            auth()->user()->save();
        }

        return $confirms;
    }

    public function isVerified() : bool
    {
        return $this->phoneData && $this->phoneData->valid;
    }

    private function saveNewPhoneNumber(LibPhoneNumber $phoneNumber, $code)
    {
        $dialCode = $phoneNumber->getCountryCode();

        $formattedPhoneNumber = PhoneNumberUtil::getInstance()->format($phoneNumber, PhoneNumberFormat::E164);

        $data = [
            'phone'    => Crypt::encrypt($formattedPhoneNumber),
            'dialCode' => $dialCode,
            'country'  => "",
            'code'     => $code,
            'valid'    => false,
            'attempt'  => $this->updateAttempt($formattedPhoneNumber),
        ];

        auth()->user()->phone = json_encode($data);
        auth()->user()->save();
    }

    private function updateAttempt($phoneNumber){

        if ($phoneNumber == $this->getNumber()) {

            return $this->phoneData->attempt + 1;

        }

        return 1;
    }
}
