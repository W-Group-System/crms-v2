<?php

namespace App\Http\Middleware;

use Closure;
use RealRashid\SweetAlert\Facades\Alert;

class DisableInactiveUsers
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
        if (auth()->check() && auth()->user()->is_active == 0)
        {
            auth()->logout();

            session()->flash('alert.error', 'Your account has been deactivated. Please contact the Administrator.');
            return redirect()->route('login');
        }
        
        return $next($request);
    }
}
