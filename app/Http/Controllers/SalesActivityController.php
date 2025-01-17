<?php

namespace App\Http\Controllers;

use App\Activity;
use Illuminate\Http\Request;

class SalesActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->search;
        $entries = $request->entries;

        $activities = Activity::where(function($q)use($user) {
                $q->where('PrimaryResponsibleUserId', $user->user_id)
                    ->orWhere('SecondaryResponsibleUserId', $user->user_id)
                    ->orWhere('PrimaryResponsibleUserId', $user->id)
                    ->orWhere('SecondaryResponsibleUserId', $user->id);
                })
                ->when($search, function($searchQuery)use($search) {
                    $searchQuery->where('ActivityNumber', 'LIKE','%'.$search.'%')
                        ->orWhereHas('client', function($clientQuery)use($search) {
                            $clientQuery->where('Name', 'LIKE','%'.$search.'%');
                        })
                        ->orWhere('Title', 'LIKE', 'Name','LIKE','%'.$search.'%');
                })
                ->where('Status', 10)
            ->paginate($entries ?? 10);
            
        return view('dashboard.sales_activity', compact('activities', 'search', 'entries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
