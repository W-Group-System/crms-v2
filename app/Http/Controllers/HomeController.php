<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard.index');
    }

    public function changePassword()
    {
        return view('layouts.change_password');
    }   

    public function myAccount()
    {
        return view('layouts.my_account');
    }

    public function updatePassword(Request $request)
    {   
        $request->validate([
            // 'old_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
        ]);

        // if(!Hash::check($request->old_password, auth()->user()->password)){
        //     return back()->with("error", "Old password doesn't match!");
        // }
           
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with("status", "Password changed successfully!");
    }
}
