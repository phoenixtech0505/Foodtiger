<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PhoneVerificationController extends Controller
{
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedPhone()
                        ? redirect()->route('home')
                        : view('auth.verify_sms');
    }

    public function verify(Request $request)
    {
        if ($request->user()->verification_code !== $request->code) {
            throw ValidationException::withMessages([
                'code' => ['The code your provided is wrong. Please try again or request another call.'],
            ]);
        }

        if ($request->user()->hasVerifiedPhone()) {
            return redirect()->route('home');
        }

        $request->user()->markPhoneAsVerified();

        return redirect()->route('home')->with('status', 'Your phone was successfully verified!');
    }
}
