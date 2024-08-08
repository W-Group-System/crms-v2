<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function transaction_summary()
    {
        return view('reports.transaction_summary');
    }
}
