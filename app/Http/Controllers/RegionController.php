<?php

namespace App\Http\Controllers;
use App\Region;
use Validator;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    // List
    public function index()
    {
        if(request()->ajax())
        {
            return datatables()->of(Region::latest()->get())
                    ->addColumn('action', function($data){
                        $buttons = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary">Edit</button>';
                        $buttons .= '&nbsp;&nbsp;';
                        $buttons .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger">Delete</button>';
                        return $buttons;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('regions.index');
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
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'Type'          =>  $request->Type,
            'Name'          =>  $request->Name,
            'Description'   =>  $request->Description
        );

        Region::create($form_data);

        return response()->json(['success' => 'Data Added Successfully.']);
    }

    // Edit
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = Region::findOrFail($id);
            return response()->json(['data' => $data]);
        }
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
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'Type'          =>  $request->Type,
            'Name'          =>  $request->Name,
            'Description'   =>  $request->Description
        );

        Region::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }

    // Delete
    public function delete($id)
    {
        $data = Region::findOrFail($id);
        $data->delete();
    }
}
