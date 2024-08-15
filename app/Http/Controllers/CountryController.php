<?php

namespace App\Http\Controllers;
use App\Country;
use Validator;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CountryController extends Controller
{
    // List
    public function index(Request $request)
    {
        $country = Country::when($request->search, function($query)use($request) {
                $query->where('Name', $request->search)->orWhere('Description', $request->Description);
            })
            ->latest()
            ->paginate($request->entries ?? 10);

        return view('countries.index',
            array(
                'country' => $country,
                'entries' => $request->entries,
                'search' => $request->search
            )
        );
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
            return response()->json(['errors' => $error->errors()->all(), 'status' => 0]);
        }

        $form_data = array(
            'Name'          =>  $request->Name,
            'Description'   =>  $request->Description
        );

        Country::create($form_data);

        return response()->json(['success' => 'Data Added Successfully.', 'status' => 1]);
    }
    
    // Edit
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = Country::findOrFail($id);
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
            return response()->json(['errors' => $error->errors()->all(), 'status' => 0]);
        }

        $form_data = array(
            'Name'          =>  $request->Name,
            'Description'   =>  $request->Description
        );

        Country::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.', 'status' => 1]);
    }

    // Delete
    public function delete($id)
    {
        $data = Country::findOrFail($id);
        $data->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }
}
