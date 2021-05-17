<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function getToken(Request $request)
    {
        $user = User::where(['active'=>1, 'email'=>$request->email])->first();
        if ($user != null) {
            if (Hash::check($request->password, $user->password)) {
                if ($user->hasRole(['client'])) {
                    return response()->json([
                        'status' => true,
                        'token' => $user->api_token,
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'errMsg' => 'User is not a client!',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'errMsg' => 'Incorrect password!',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'errMsg' => 'User not found. Incorrect email!',
            ]);
        }
    }

    public function register(Request $request)
    {
        
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'unique:users', 'max:255'],
                'phone' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
                'password' => ['required', 'string', 'min:8'],
            ]);

            if (!$validator->fails()) {
                $client = new User;

                $client->name = $request->name;
                $client->email = $request->email;
                $client->phone = $request->phone;
                $client->password = Hash::make($request->password);
                $client->api_token = Str::random(80);
                $client->save();

                //Assign role
                $client->assignRole('client');

                return response()->json([
                    'status' => true,
                    'token' => $client->api_token,
                    'id' => $client->id,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'errMsg' => $validator->errors(),
                ]);
            }
        
    }

    public function loginFacebook(Request $request)
    {

            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'max:255'],
                'fb_id'=>['required', 'string']
            ]);

            if (!$validator->fails()) {
                $client = User::where('email', $request->email)->first();

                if (! $client) {
                    $client = new User;
                    $client->fb_id = $request->fb_id;
                    $client->name = $request->name;
                    $client->email = $request->email;
                    $client->api_token = Str::random(80);
                    $client->save();

                    $client->assignRole('client');
                } else {
                    if (empty($client->fb_id)) {
                        $client->fb_id = $request->fb_id;
                    }

                    $client->update();
                }

                return response()->json([
                    'status' => true,
                    'token' => $client->api_token,
                    'id' => $client->id,
                    'msg' => 'Client logged in!',
                ]);

            }else{
                return response()->json([
                    'status' => false,
                    'errMsg' => $validator->errors(),
                ]);
            }

            
        
    }

    public function loginGoogle(Request $request)
    {
       
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'max:255'],
                'google_id'=>['required', 'string']
            ]);
        if(!$validator->fails()){
            $client = User::where('email', $request->email)->first();

            if (! $client) {
                $client = new User;
                $client->google_id = $request->google_id;
                $client->name = $request->name;
                $client->email = $request->email;
                $client->api_token = Str::random(80);
                $client->save();

                $client->assignRole('client');
            } else {
                if (empty($client->google_id)) {
                    $client->google_id = $request->google_id;
                }

                $client->update();
            }

            return response()->json([
                'status' => true,
                'token' => $client->api_token,
                'id' => $client->id,
                'msg' => 'Client logged in!',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errMsg' => $validator->errors(),
            ]);
        }
    }
  
    public function getUseData()
    {
        $user = User::where(['api_token' => $_GET['api_token']])->first();

        if ($user) {
            return response()->json([
                'status' => true,
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ? $user->phone : '',
                ],
                'msg' => 'User found!',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errMsg' => 'User not found!',
            ]);
        }
    }
}
