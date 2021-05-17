<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class CheckIfAdmin
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
        // let's check, if the current user is logged in
        if (Auth::check()) {

            // if he is logged in, check, if he is an admin
            if (Auth::user()->hasRole('admin')) {

                // if yes, he can pass...
                return $next($request);
            }

            // ... otherwise redirect him to another location of your choice...
            //return redirect('somewhere');
            return redirect('/login');
        }
        // ... for example to the login-page
        return redirect('/login');
    }
}
