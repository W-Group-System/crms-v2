<?php

namespace App\Http\Controllers;

use App\Client;
use App\ConcernDepartment;
use App\IssueCategory;
use Illuminate\Http\Request;

class CustomerSatisfactionController extends Controller
{
    public function header()
    {
        return view('customer_service.cs_index');
    }

    public function index()
    {
        $client = Client::all();
        $concern_department = ConcernDepartment::all();
        $category = IssueCategory::all();
        return view('customer_service.customer_satisfaction', compact('client', 'concern_department', 'category'));
    }
    
}
