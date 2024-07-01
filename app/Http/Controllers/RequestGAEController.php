<?php

namespace App\Http\Controllers;
use App\RequestGAE;
use Validator;
use Illuminate\Http\Request;

class RequestGAEController extends Controller
{
    // List
    public function index()
    {   
        if(request()->ajax())
        {
            $paymentTerms = RequestGAE::orderBy('id', 'desc')->get();
            return datatables()->of($paymentTerms)
                    ->addColumn('action', function($data){
                        $buttons = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary">Edit</button>';
                        $buttons .= '&nbsp;&nbsp;';
                        $buttons .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger">Delete</button>';
                        return $buttons;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('request_gaes.index'); 
    }

    // Store
    public function store(Request $request) 
    {
        $rules = array(
            'ExpenseName'   =>  'required',
            'Cost'          =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'ExpenseName'   =>  $request->ExpenseName,
            'Cost'          =>  $request->Cost
        );

        RequestGAE::create($form_data);

        return response()->json(['success' => 'Data Added Successfully.']);
    }  

    // Edit
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = RequestGAE::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $rules = array(
            'ExpenseName'   =>  'required',
            'Cost'          =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'ExpenseName'   =>  $request->ExpenseName,
            'Cost'          =>  $request->Cost
        );

        RequestGAE::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }

    // Delete
    public function delete($id)
    {
        $paymentTerms = RequestGAE::findOrFail($id);
        $paymentTerms->delete();
    }
}
