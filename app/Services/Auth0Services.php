<?php

namespace App\Services;

use App\User;
use Auth0\SDK\API\Management;

class Auth0Services
{
    public static function syncV1UsersToAuth0()
    {
        //1. Get all users that need to be sync
        $users = User::where('email', '!=', auth()->user()->email)->whereNull('google_id')->whereNull('fb_id')->get();

        $results = [];
        $mgmt_api = new Management(config('settings.auth0_token'), getenv('AUTH0_DOMAIN'));

        foreach ($users as $key => $user) {
            try {
                $resultsOfAuth0 = $mgmt_api->users()->create([
                    'email' => $user['email'],
                    'password' => substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=~!@#$%^&*()_+,'), 0, 75),
                    'email_verified' => true,
                    'connection' => config('settings.auth_connection'),
                ]);
                array_push($results, ['user'=>$user, 'message'=>'Created']);
            } catch (\Throwable $th) {
                array_push($results, ['user'=>$user, 'message'=>'Error on calling Auth0 or user already exists']);
            }
        }

        return $results;
    }
}
