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
        
        $user = Auth::user();

        // Check if the user is authenticated and has a role
        if ($user && $user->role) {
            $roleType = $user->role->type; // Get the role type

            // Check the role type and redirect accordingly
            if ($roleType == 'RND' || $roleType == 'ITD' || $roleType == 'ACCTG') {
                return redirect('/dashboard-rnd');
            } elseif ($roleType == 'IS' || $roleType == 'LS' || $roleType == 'CS') {
                return redirect('/dashboard-sales');
            } elseif ($roleType == 'QCD-WHI' || $roleType == 'QCD-PBI' || $roleType == 'QCD-MRDC' || $roleType == 'QCD-CCC') {
                return redirect('/dashboard-qcd');
            } elseif ($roleType == 'PRD') {
                return redirect('/dashboard-prd');
            }

        }

        // If no valid user or role, redirect to the login page
        return redirect()->route('login');
    }

}
