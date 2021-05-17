<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function googleRedirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function googleHandleProviderCallback()
    {
        $user_google = Socialite::driver('google')->stateless()->user();

        // $user->token;
        $user = User::where('email', $user_google->email)->first();
        //$user = User::where('google_id', $user_google->id)->first();
        if (! $user) {
            $user = new User;
            $user->google_id = $user_google->id;
            $user->name = $user_google->name;
            $user->email = $user_google->email;
            $user->api_token = Str::random(80);
            $user->save();

            $user->assignRole('client');
        } else {
            if (empty($user->google_id)) {
                $user->google_id = $user_google->id;
            }

            $user->update();
        }

        // login
        Auth::loginUsingId($user->id);

        return redirect('/');
    }

    public function facebookRedirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function facebookHandleProviderCallback()
    {
        $user_facebook = Socialite::driver('facebook')->stateless()->user();

        // $user->token;
        $user = User::where('email', $user_facebook->email)->first();
        //$user = User::where('fb_id', $user_facebook->id)->first();
        if (! $user) {
            $user = new User;
            $user->fb_id = $user_facebook->id;
            $user->name = $user_facebook->name;
            $user->email = $user_facebook->email;
            $user->api_token = Str::random(80);
            $user->save();

            $user->assignRole('client');
        } else {
            if (empty($user->fb_id)) {
                $user->fb_id = $user_facebook->id;
            }

            $user->update();
        }
        // login
        Auth::loginUsingId($user->id);

        return redirect('/');
    }

    /*public function username()
    {
        return 'phone';
    }*/
}
