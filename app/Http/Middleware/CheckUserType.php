<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class CheckUserType
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
        $role = optional(Auth::user())->role;

        // Check if the role object exists before accessing its 'type' property
        if ($role && $role->type == 'RND') {
            return redirect('/dashboard-rnd');
        } elseif ($role && $role->type == 'IS') {
            return redirect('/dashboard-sales');
        }
        return redirect()->route('login');

        return $next($request);
    }
}
