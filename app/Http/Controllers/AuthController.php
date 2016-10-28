<?php

namespace Sneefr\Http\Controllers;

use Illuminate\Contracts\Encryption\Encrypter;
use Laravel\Socialite\Facades\Socialite;
use Sneefr\Events\UserRegistered;
use Sneefr\Models\ActionLog;
use Sneefr\Models\User;

class AuthController extends Controller
{
    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    public function callback()
    {
        dd(Socialite::driver('facebook')->user());
        // add required fields not provided by default
        $providerUser = Socialite::driver('facebook')->fields(['email', 'first_name', 'last_name'])->user();

        $user = User::where('facebook_id', $providerUser->getId())->first();

        if (!$user) {
            $user = User::where('email',$providerUser->getEmail())->OrWhere('facebook_email', $providerUser->getEmail())->get()->first();

            if($providerUser->getEmail() && $user) {
                return redirect()->back()->with('warning', trans('login.account_by_fb_exist'));
            }

            $user = User::create([
                'email' => $providerUser->getEmail(),
                'facebook_email' => $providerUser->getEmail(),
                'facebook_id' => $providerUser->getId(),
                'given_name' => $providerUser->user['first_name'],
                'surname' => $providerUser->user['last_name'],
                'email_verified' => 0,
                'verified' => 1,
                'locale'   => config('app.locale'),
            ]);

            event(new UserRegistered($user));
        }

        auth()->login($user, true);
        return redirect()->intended();
    }

    /**
     * Log the user out of the application.
     *
     * @return Response
     */
    public function logout()
    {
        // Log the logout event.
        if (auth()->check()) {
            ActionLog::create([
                'type'    => ActionLog::USER_LOGOUT,
                'user_id' => auth()->id()
            ]);
        }

        \Auth::logout();
        \Session::flush();

        return redirect('/login');
    }

    /**
     * Activate account using data from email link
     *
     * @param $key
     * @param Encrypter $encrypter
     * @return \Illuminate\Http\RedirectResponse
     */
    public function Activate($key, Encrypter $encrypter)
    {
        // Decrypt the verification key
        $data = $encrypter->decrypt($key);

        // Basic check
        if ( !empty($data) &&  isset($data['id']) && isset($data['email']) )
        {
            // Get the person based on this user identifier
            $user = User::findOrFail($data['id']);

            if($user->getEmail() == $data['email'] && !$user->isVerified())
            {
                $user->email_verified = true;
                $user->verified = true;
                $user->save();

                return redirect()->route('home')->with('success', trans('feedback.email_activation_success'));
            }
        }

        return redirect()->route('home')->with('error', trans('feedback.email_activation_error'));
    }
}
