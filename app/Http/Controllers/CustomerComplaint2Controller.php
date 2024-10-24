<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerComplaint2Controller extends Controller
{
    public function index()
    {
        
        return view('customer_service.customer_complaint');
    }
}
