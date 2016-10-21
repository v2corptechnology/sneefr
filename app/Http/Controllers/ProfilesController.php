<?php

namespace Sneefr\Http\Controllers;

use Auth;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Sneefr\Exceptions\ValidationException;
use Sneefr\Jobs\SendPhoneNumberVerificationCode;
use Sneefr\Jobs\VerifyEmail;
use Sneefr\Models\User;
use Sneefr\Repositories\User\UserRepository;
use Sneefr\Services\Image;


class ProfilesController extends Controller
{
    public $disk;

    /**
     * @param \Illuminate\Contracts\Filesystem\Factory $filesystemFactory
     */
    public function __construct(Factory $filesystemFactory)
    {
        $this->disk = $filesystemFactory->disk('avatars');
    }

    /**
     * Update the settings of the authenticated person.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(Request $request)
    {
        try {
            switch ($request->get('settings_category')) {
                case 'info':
                    $this->saveGeneralSettings($request->all());
                    break;
                case 'application':
                    $this->saveApplicationSettings($request->all());
                    break;
                case 'notifications':
                    $this->saveNotificationSettings($request->all());
                    break;
                case 'phoneConfirm':
                    $this->confirm($request);
                    break;
                case 'handlingPhone':
                    $this->handlingPhone($request);
                    break;
                case 'avatar':
                    $this->updateAvatar($request);
                    break;
            }
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        }

        return redirect()->back();
    }

    /**
     * Save the general settings of the authenticated person.
     *
     * @param  array  $data
     *
     * @return void
     */
    protected function saveGeneralSettings(array $data)
    {
        $userModel = User::find(auth()->id());

        // Change email address only if different from the current one
        if (!empty($data['email']) && $data['email'] !== $userModel->getEmail()) {

            $this->validateEmailAddress($data['email']);

            $userModel->update(['email' => $data['email']]);

            $this->dispatch(new VerifyEmail($userModel));
        }

        // Location data.
        $locationData = Arr::only($data, ['location', 'latitude', 'longitude']);
        if ($locationData['location']) {
            // If location data was provided, make sure it is valid.
            $this->validateLocationData($locationData);
        } else {
            // Ensure that we won’t store coordinates linked to nothing.
            $locationData['location']  = null;
            $locationData['latitude']  = null;
            $locationData['longitude'] = null;
        }

        $locationData = [
            'location' => $locationData['location'],
            'lat'      => $locationData['latitude'],
            'long'     => $locationData['longitude'],
        ];

        // Persisting the new data.
        foreach ($locationData as $key => $value) {
            $userModel->{$key} = $value;
        }

        // Persisting the new given_name or surname data.
        foreach (Arr::only($data, ['given_name', 'surname']) as $key => $value) {
            if($value != '') $userModel->{$key} = $value;
        }

        if ($userModel->save()) {
            session()->flash('success', trans('feedback.profile_edit_success'));
        }
    }

    /**
     * Check that an e-mail address is valid.
     *
     * @param  string  $email
     *
     * @return void
     *
     * @throws \Sneefr\Exceptions\ValidationException if the email is invalid
     */
    protected function validateEmailAddress($email)
    {
        $rules = ['email' => 'required|email'];

        $validator = Validator::make(['email' => $email], $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Check that location data is valid.
     *
     * @param  array  $data
     * @param  array  $extraRules
     *
     * @return void
     *
     * @throws \Sneefr\Exceptions\ValidationException if the data is invalid
     */
    protected function validateLocationData(array $data, array $extraRules = [])
    {
        $rules = [
            'location'  => 'required',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ];

        $rules = array_merge($rules, $extraRules);

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Save the application settings of the authenticated person.
     *
     * @param  array  $data
     *
     * @return void
     */
    protected function saveApplicationSettings(array $data)
    {
        // Save the language locale.

        $config = app('Illuminate\Contracts\Config\Repository');
        $supportedLocales = $config->get('app.supported_locales');

        if (!empty($data['locale']) && in_array($data['locale'], $supportedLocales)) {

            // Updating the value that is currently present in the session.
            $sessionManager = app('Illuminate\Session\SessionManager');
            $sessionManager->set('lang', $data['locale']);

            // Persisting the new data.
            // TODO: remove dependency on Eloquent.
            User::find(auth()->id())->update(['locale' => $data['locale']]);

            session()->flash('success', trans('feedback.profile_edit_success'));
        }
    }

    /**
     * Save the notification settings of the authenticated person.
     *
     * @param  array  $data
     *
     * @return void
     */
    protected function saveNotificationSettings(array $data)
    {
        User::find(auth()->id())
            ->update(['preferences' => [
                'daily_digest' => isset($data['daily_digest'])
            ]]);

        session()->flash('success', trans('feedback.profile_edit_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @param UserRepository $user
     * @param DiscussionRepository $discussion
     *
     * @return Response
     */
    public function destroy($id, UserRepository $user, DiscussionRepository $discussion)
    {
        if (auth()->id() == $id) {
            $user = User::findOrFail(auth()->id());
            // TODO: delete ads (status account desactivated)
            $user->ads()->delete();

            Follow::where('user_id', $id)->delete();
            Follow::where('followable_id', $id)->where('followable_type', 'user')->delete();

            $user->likes->delete();

            $user->sneefs()->delete();

            $user->notifications()->delete();

            $user->delete();

            \Auth::logout();

            return redirect()->route('home')->with('success', trans('feedback.profile_deleted_success'));
        }
    }

    /**
     * Validate an email based on the key we receive
     * and eventually update the gamification objectives
     *
     * @param int                                        $userId
     * @param string                                     $key
     * @param \Illuminate\Contracts\Encryption\Encrypter $encrypter
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmEmail(int $userId, string $key, Encrypter $encrypter)
    {
        // Get the person based on this user identifier
        $user = User::findOrFail($userId);

        // Decrypt the verification key
        $data = $encrypter->decrypt($key);

        // Basic check
        if (empty($data) || ! isset($data['id']) || ! isset($data['email']) || $user->getId() != $data['id']) {
            return redirect()->route('home')->with('error', trans('feedback.email_validation_error'));
        }

        // Save the verified email
        $user->email = $data['email'];
        $user->email_verified = true;
        $user->save();

        return redirect()->route('home')->with('success', trans('feedback.email_validation_success'));
    }

    /**
     * Send the validation code.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function handlingPhone(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|phone:AUTO',
        ]);

        $phone = auth()->user()->phone;

        if ($phone->canAskCode() || $phone->getNumber() != $request->get('phone')) {
            
            $phone->update($request->get('phone'));
            
            session()->flash('phoneConfirm', true);
            session()->flash('error', trans('feedback.phone_verification_waiting'));

        } else {
            session()->flash('error', trans('feedback.phone_verification_error'));
        }
    }

    /**
     * Handle confirm code.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm(Request $request)
    {
        $this->validate($request, [
            'code_confirm' => 'required|numeric',
        ]);

        $confirmCode = $request->get('code_confirm');

        if (! auth()->user()->phone->confirm($confirmCode)) {
            session()->flash('phoneConfirm', true);
            session()->flash('error', trans('profile.phone_verification_fail'));
        } else {
            session()->flash('success', trans('feedback.phone_verification_success'));
        }
    }

    protected function updateAvatar(Request $request)
    {
        $this->validate($request, [
            'avatar' => 'required|image',
        ]);

        $imageService = new Image();
        $extention = $request->file('avatar')->extension();;
        $name = auth()->user()->getId() . '.' . $extention;
        // Path for uploading
        $path = "avatar/" . $name;

       //$file = $request->file('avatar');
        $image = $imageService::standardize($request->file('avatar'));

        // Move the file
        if (! $this->disk->put($path, $image)) {
            // add logs
            return redirect()->back()->with('error', trens('feedback.profile_edit_avatar_error'));
        }

        auth()->user()->avatar = $name;
        auth()->user()->save();

        return redirect()->back()->with('success', trans('feedback.profile_edit_avatar_success'));
    }
}
