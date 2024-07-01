<?php

namespace App\Http\Controllers;

use App\Department;
use Validator;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // List
    public function index()
    {
        $departments = Department::with('company')->latest()->get();
        $companies = $departments->pluck('company')->unique('id');
        if(request()->ajax())
        {
            return datatables()->of($departments)
                    ->addColumn('action', function($data){
                        $buttons = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary">Edit</button>';
                        $buttons .= '&nbsp;&nbsp;';
                        $buttons .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger">Delete</button>';
                        return $buttons;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('departments.index', compact('departments', 'companies'));
    }
    
    // Store
    public function store(Request $request) 
    {
        $rules = array(
            'company_id'    =>  'required',
            'name'          =>  'required',
            'description'   =>  'required'
        );


        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'company_id'    =>  $request->company_id,
            'name'          =>  $request->name,
            'description'   =>  $request->description
        );

        Department::create($form_data);

        return response()->json(['success' => 'Data Added Successfully.']);
    }

    // Edit
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = Department::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $rules = array(
            'company_id'    =>  'required',
            'name'          =>  'required',
            'description'   =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'company_id'    =>  $request->company_id,
            'name'          =>  $request->name,
            'description'   =>  $request->description
        );

        Department::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }

    // Delete
    public function delete($id)
    {
        $data = Department::findOrFail($id);
        $data->delete();
    }
}
