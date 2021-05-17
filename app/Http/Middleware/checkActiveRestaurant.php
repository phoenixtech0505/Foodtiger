<?php

namespace App\Http\Middleware;

use App\Restorant;
use Closure;
use Illuminate\Support\Facades\URL;

class checkActiveRestaurant
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
        $tmp = explode('/', URL::current());
        $alias = end($tmp);
        $restorant = Restorant::where('subdomain', $alias)->first();

        if ($restorant->active == 1) {
            return $next($request);
        } else {
            return redirect('/');
        }
    }
}
