<?php

namespace App\Http\Controllers;

use App\CustomerComplaint2;
use App\CustomerSatisfaction;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerServiceController extends Controller
{
    public function index(Request $request)
    {
        $entries = $request->entries;
        $search = $request->search;

        $cs = CustomerSatisfaction::where('Status', 10)
            ->where(function ($q) use ($search) {
                $q->where('CsNumber', 'LIKE', '%' . $search . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $search . '%')
                    ->orWhere('CompanyName', 'LIKE', '%' . $search . '%')
                    ->orWhere('ContactName', 'LIKE', '%' . $search . '%')
                    ->orWhere('Email', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('concerned', function ($clientQuery) use ($search) {
                        $clientQuery->where('Name', 'LIKE', '%' . $search . '%');
                    });
            })
            ->orderBy('id', 'desc')
            ->get();

        $cc = CustomerComplaint2::where('Status', 10)
            ->where(function ($q) use ($search) {
                $q->where('CcNumber', 'LIKE', '%' . $search . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $search . '%')
                    ->orWhere('CompanyName', 'LIKE', '%' . $search . '%')
                    ->orWhere('ContactName', 'LIKE', '%' . $search . '%')
                    ->orWhere('Email', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('concerned', function ($clientQuery) use ($search) {
                        $clientQuery->where('Name', 'LIKE', '%' . $search . '%');
                    });
            })
            ->orderBy('id', 'desc')
            ->get();


        $sortedResults = $cc
        ->concat($cs);

        $page = request()->get('page', 1);
        $perPage = $entries ?? 10;
        $paginatedResults = new LengthAwarePaginator(
            $sortedResults->forPage($page, $perPage),
            $sortedResults->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('dashboard.cs_transactions',
            array(
                'paginatedResults' => $paginatedResults,
                'search' => $search,
                'entries' => $entries
            )
        );
    }
}
