<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use Validator;
use DataTables;

class CompanyController extends Controller
{
    // List
    public function index()
    {   
        if(request()->ajax())
        {
            return datatables()->of(Company::latest()->get())
                    ->addColumn('action', function($data){
                        $buttons = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary">Edit</button>';
                        $buttons .= '&nbsp;&nbsp;';
                        $buttons .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger">Delete</button>';
                        return $buttons;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('companies.index'); 
    }
    // Create
    public function store(Request $request) 
    {
        $rules = array(
            'name'          =>  'required',
            'description'   =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'name'          =>  $request->name,
            'description'   =>  $request->description
        );

        Company::create($form_data);

        return response()->json(['success' => 'Data Added Successfully.']);
    }
    // Edit
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = Company::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }
    // update
    public function update(Request $request, $id)
    {
        $rules = array(
            'name'          =>  'required',
            'description'   =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'name'          =>  $request->name,
            'description'   =>  $request->description
        );

        Company::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }
    // delete
    public function delete($id)
    {
        $data = Company::findOrFail($id);
        $data->delete();
    }
}
