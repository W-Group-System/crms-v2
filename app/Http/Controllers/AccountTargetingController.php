<?php

namespace App\Http\Controllers;

use App\AccountTargeting;
use Illuminate\Http\Request;

class AccountTargetingController extends Controller
{
   
    public function index(Request $request)
    {
        $accountTargets = AccountTargeting::paginate(10);;
        return view('account_targeting.index', compact('accountTargets')); 
    }


}