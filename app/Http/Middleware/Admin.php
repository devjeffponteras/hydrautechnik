<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()){
            if(Auth::user()->role_id == 6 || Auth::user()->role_id == 2){
                // abort('403','Unauthorized page access');
                Auth::logout();
                return response()->view('components.unauthorize-access');
            }
            else {
                return $next($request);
            }
        } else {
            return redirect(route('panel.login'));
        }
    }
}
