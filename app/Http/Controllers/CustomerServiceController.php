<?php

namespace App\Http\Controllers;

use App\CustomerComplaint2;
use App\CustomerSatisfaction;   
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerServiceController extends Controller
{
    public function index(Request $request)
    {
        $entries = $request->entries;
        $search = $request->search;
        $role = auth()->user()->role;
        $userId = Auth::id(); 
        $userByUser = Auth::user()->user_id; 

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
            ->when(isset($role) && in_array($role->type, ['RND', 'QCD-WHI', 'QCD-PBI', 'QCD-MRDC', 'QCD-CCC']) && in_array($role->name, ['Staff L1', 'Staff L2']), function ($q) {
                $q->whereHas('concerned', function($q) {
                    $q->where('Department',  auth()->user()->role->type);
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
            ->when(isset($role) && in_array($role->type, ['RND', 'QCD-WHI', 'QCD-PBI', 'QCD-MRDC', 'QCD-CCC']) && in_array($role->name, ['Staff L1', 'Staff L2']), function ($q) {
                $q->whereHas('concerned', function($q) {
                    $q->where('Department',  auth()->user()->role->type);
                });
            })
            ->orderBy('id', 'desc')
            ->get();

       
        $sortedResults = $cc
            ->concat($cs)
            ->sortByDesc(function ($item) {
                // This creates a tuple [ProgressFlag, id]
                return [$item->Progress == 10 ? 1 : 0, $item->id];
            })
            ->values();

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
