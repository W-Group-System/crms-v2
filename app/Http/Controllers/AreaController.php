<?php

namespace App\Http\Controllers;
use App\Area;
use App\Region;
use Validator;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    // List
    public function index()
    {
        $areas = Area::with('region')->orderBy('id', 'desc')->get();
        $regions = Region::all();

        if (request()->ajax()) 
        {
            return datatables()->of($areas)
                ->addColumn('action', function ($data) {
                    $buttons = '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-primary">Edit</button>';
                    $buttons .= '&nbsp;&nbsp;';
                    $buttons .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger">Delete</button>';
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('areas.index', compact('areas', 'regions'));
    }

    // Store
    public function store(Request $request) 
    {
        $rules = array(
            // 'Type'          =>  'required',
            // 'Region'        =>  'required',
            'Name'          =>  'required',
            'Description'   =>  'required'
        );


        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'Type'          =>  $request->Type,
            'RegionId'      =>  $request->RegionId,
            'Name'          =>  $request->Name,
            'Description'   =>  $request->Description
        );

        Area::create($form_data);

        return response()->json(['success' => 'Data Added Successfully.']);
    }

    // Edit
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = Area::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $rules = array(
            'Type'          =>  'required',
            // 'Region'        =>  'required',
            'Name'          =>  'required',
            'Description'   =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'Type'          =>  $request->Type,
            'RegionId'      =>  $request->RegionId,
            'Name'          =>  $request->Name,
            'Description'   =>  $request->Description
        );

        Area::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }

    // Delete
    public function delete($id)
    {
        $data = Area::findOrFail($id);
        $data->delete();
    }
}