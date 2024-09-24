<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierProductController extends Controller
{
    // List
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'Name'); // Default to 'Name' if no sort is specified
        $direction = $request->get('direction', 'asc'); // Default to ascending order
        $entries = $request->input('number_of_entries', 10); // Default to 10 entries per page

        return view('spe.index', [
            'search' => $search,
            'entries' => $entries
        ]);
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
