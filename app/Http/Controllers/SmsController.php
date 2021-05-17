<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Twilio\Jwt\ClientToken;

class SmsController extends Controller
{
    protected $code;
    protected $smsVerifcation;

    public function __construct()
    {
        $this->smsVerifcation = new \App\SmsVerification();
    }

    public function store(Request $request)
    {
        $code = rand(1000, 9999); //generate random code
        $request['code'] = $code; //add code in $request body
        $this->smsVerifcation->store($request); //call store method of model

        return $this->sendSms($request); // send and return its response
    }

    public function verifyContact(Request $request)
    {
        $smsVerifcation = $this->smsVerifcation::where('contact_number', '=', $request->contact_number)->latest() //show the latest if there are multiple
                                                                                                    ->first();
        if ($request->code == $smsVerifcation->code) {
            $request['status'] = 'verified';

            //return $smsVerifcation->updateModel($request);
            $smsVerifcation->updateModel($request);

            $msg['message'] = 'verified';
            //return $msg;
            return redirect()->route('home');
        } else {
            $msg['message'] = 'not verified';
            //return $msg;

            return redirect()->route('sms.verify');
        }
    }

    public function sendSms($request)
    {
        $accountSid = config('app.twilio')['TWILIO_ACCOUNT_SID'];
        $authToken = config('app.twilio')['TWILIO_AUTH_TOKEN'];
        try {
            $client = new Client(['auth' => [$accountSid, $authToken]]);
            $result = $client->post('https://api.twilio.com/2010-04-01/Accounts/'.$accountSid.'/Messages.json',
            ['form_params' => [
                'Body' => 'CODE: '.$request->code, //set message body
                'To' => $request->contact_number,
                'From' => '+16504108569', //we get this number from twilio
            ]]);

            return $result;
        } catch (Exception $e) {
            echo 'Error: '.$e->getMessage();
        }
    }

    public function verifyPage()
    {
        return view('auth.verify_sms');
    }
}
