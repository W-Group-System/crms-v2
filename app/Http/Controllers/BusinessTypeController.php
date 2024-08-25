<?php

namespace App\Http\Controllers;
use App\BusinessType;
use App\Exports\BusinessTypeExport;
use Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class BusinessTypeController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $fetchAll = $request->input('fetch_all', false);
        
        $businessType = BusinessType::when($request->search, function($query)use($request) {
                $query->where('Name', 'LIKE', '%'.$request->search.'%')->orWhere('Description', 'LIKE', '%'.$request->search.'%');
            })
            ->latest();


        if ($fetchAll)
        {
            $businessType = $businessType->get();

            return response()->json($businessType);
        }
        else
        {
            $businessType = $businessType->paginate($request->entries ?? 10);

            return view('business_types.index', 
                array(
                    'bussinessType' => $businessType,
                    'search' => $request->search,
                    'entries' => $request->entries
                )
            ); 
        }
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
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'Name'          =>  $request->Name,
            'Description'   =>  $request->Description
        );

        BusinessType::create($form_data);

        return response()->json(['success' => 'Data Added Successfully.']);
    }

    // Edit
    public function edit($id)
    {
        $data = BusinessType::findOrFail($id);
        return response()->json(['data' => $data]);
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

        BusinessType::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }

    // Delete
    public function delete($id)
    {
        $data = BusinessType::findOrFail($id);
        $data->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }

    public function export()
    {
        return Excel::download(new BusinessTypeExport, 'Business Type.xlsx');
    }
}
