<?php

namespace App\Http\Controllers;
use App\NatureRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class NatureRequestController extends Controller
{
    // List
    public function index()
    {   
        if(request()->ajax())
        {
            return datatables()->of(NatureRequest::query())
                    ->addColumn('action', function($data){
                        $buttons = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-table"><i class="ti-pencil"></i></button>';
                        $buttons .= '&nbsp;&nbsp;';
                        $buttons .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-table"><i class="ti-trash"></i></button>';
                        return $buttons;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('nature_requests.index'); 
    }

    // Store
    public function store(Request $request) 
    {
        $rules = array(
            'Name'          =>  'required',
            'Description'   =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            $errors = $error->errors()->toArray();
            $formattedErrors = [];
            
            foreach ($errors as $field => $messages) {
                foreach ($messages as $message) {
                    $formattedErrors[] = [
                        'field' => $field,
                        'message' => $message
                    ];
                }
            }

            return response()->json(['errors' => $formattedErrors]);
        }

        $form_data = array(
            'Name'          =>  $request->Name,
            'Description'   =>  $request->Description
        );

        NatureRequest::create($form_data);

        return response()->json(['success' => 'Data Added Successfully.']);
    }

    // Edit
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = NatureRequest::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $rules = array(
            'Name'          =>  'required',
            'Description'   =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'Name'          =>  $request->Name,
            'Description'   =>  $request->Description
        );

        NatureRequest::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }

    // Delete
    public function delete($id)
    {
        $natureRequest = NatureRequest::findOrFail($id);
        $natureRequest->delete();

        // Optionally, return a response
        return response()->json(['success' => 'Nature of Request has been deleted.']);
    }
}
