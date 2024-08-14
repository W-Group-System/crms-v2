<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    // Price Request
    public function price_summary()
    {
        return view('reports.price_summary');
    }

    // Transaction/Activity
    public function transaction_summary()
    {
        return view('reports.transaction_summary');
    }
}
