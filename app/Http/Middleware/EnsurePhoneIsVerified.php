<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class EnsurePhoneIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && config('settings.enable_sms_verification')) {
            if (! $request->user()->hasVerifiedPhone()) {
                return redirect()->route('phoneverification.notice');
            }
        }

        return $next($request);
    }
}
