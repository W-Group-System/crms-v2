<?php

namespace App\Http\Controllers;
use App\Region;
use Validator;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class RegionController extends Controller
{
    // List
    public function index(Request $request)
    {
        $region = Region::when($request->search, function($query)use($request) {
                $query->where('Name', 'LIKE', '%'.$request->search.'%')->orWhere('Description', 'LIKE', '%'.$request->search.'%');
            })
            ->latest()
            ->paginate($request->entries ?? 10);

        return view('regions.index',
            array(
                'region' => $region,
                'search' => $request->search,
                'entries' => $request->entries
            )
        );
    }

    // Store
    public function store(Request $request) 
    {
        $rules = array(
            'Type'          =>  'required',
            'Name'          =>  'required',
            'Description'   =>  'required'
        );


        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all(), 'status' => 0]);
        }

        $region = new Region;
        $region->Type = $request->Type;
        $region->Name = $request->Name;
        $region->Description = $request->Description;
        $region->save();

        return response()->json(['message' => 'Data Added Successfully.', 'status' => 1]);
    }

    // Edit
    public function edit($id)
    {
        $data = Region::findOrFail($id);

        return response()->json(['data' => $data]);
    }

    // Update
    public function update(Request $request, $id)
    {
        $rules = array(
            'Type'          =>  'required',
            'Name'          =>  'required',
            'Description'   =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all(), 'status' => 0]);
        }

        $region = Region::findOrFail($id);
        $region->Type = $request->Type;
        $region->Name = $request->Name;
        $region->Description = $request->Description;
        $region->save();

        return response()->json(['message' => 'Data is Successfully Updated.', 'status' => 1]);
    }

    // Delete
    public function delete($id)
    {
        $data = Region::findOrFail($id);
        $data->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }
}
