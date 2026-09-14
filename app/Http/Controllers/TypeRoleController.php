<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TypeRole;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
class TypeRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        // Search
        $search = $request->input('search');
        $typeRole = TypeRole::where(function ($query) use ($search){
            $query->where('roleType', 'LIKE', '%' . $search.'%' );
        })
        ->when($request->filter_status, function($query)use($request) {
                $query->where('status', $request->filter_status);
        })
        ->orderBy('id', 'desc')
        ->paginate($request->entries ?? 10);

        // End search
        return view('types_role.index',[
            'search' => $search,
            'typeRole' =>$typeRole,
            'entries' =>$request->entries
        ]);
        // dd($typeRole);
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
        $validator = Validator::make($request->all(), [
            'roleType' => 'required|array',
            'roleType.*' => 'required|string|max:255|distinct|unique:type_roles,roleType',
        ], [
            
            'roleType.*.unique' => 'This role type already exists.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('show_add_modal', true);
        }

        foreach ($request->roleType as $roleType) {
            TypeRole::create([
                'roleType' => $roleType,
            ]);
        }

        Alert::success('Successfully Saved')->persistent('Dismiss');

        return back();
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
        $validator = Validator::make($request->all(), [
            'roleType' => 'required|string|max:255|unique:type_roles,roleType,' . $id,
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('edit_modal_id', $id);
        }

        $types = TypeRole::findOrFail($id);

        $types->update([
            'roleType' => $request->roleType
        ]);

        Alert::success('Successfully saved changes')->persistent('Dismiss');
        return back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $types = TypeRole::findOrFail($id);
        $types->update([
            'status' => 'Inactive'
        ]);

        Alert::success('Successfully deleted successfully')->persistent('Dismiss');
        return back();
    }
}
