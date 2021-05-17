<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\WelcomeNotification;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
        if (config('settings.enable_birth_date_on_register') && config('settings.minimum_years_to_register')) {
            $rules['birth_date'] = 'required|date|date_format:Y-m-d|before:-'.config('settings.minimum_years_to_register').' years';
        }
        //dd($rules);
        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        /*return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'api_token' => Str::random(80)
        ]);*/

        //dd($data);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'api_token' => Str::random(80),
            'birth_date' => isset($data['birth_date']) ? $data['birth_date'] : '',
        ]);

        $user->assignRole('client');

        //Send welcome email
        //$user->notify(new WelcomeNotification($user));

        return $user;
    }

    protected function registered(Request $request, User $user)
    {
        if (config('settings.enable_sms_verification')) {
            $user->callToVerify();
        }

        return redirect($this->redirectPath());
    }
}
