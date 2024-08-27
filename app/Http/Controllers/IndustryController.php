<?php

namespace App\Http\Controllers;

use App\Exports\IndustryExport;
use App\Industry;
use Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class IndustryController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $fetchAll = $request->input('fetch_all', false);

        $industry = Industry::when($request->search, function($query)use($request) {
              $query->where('Name', 'LIKE', '%'.$request->search.'%')->orWhere('Description', 'LIKE', '%'.$request->search.'%');
            })
            ->latest();

        if($fetchAll)
        {
            $industry = $industry->get();
            return response()->json($industry);
        }
        else
        {
            $industry = $industry->paginate($request->entries ?? 10);

            return view('industries.index',
                array(
                    'industry' => $industry,
                    'entries' => $request->entries,
                    'search' => $request->search
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

        Industry::create($form_data);

        return response()->json(['success' => 'Data Added Successfully.']);
    }

    // Edit
    public function edit($id)
    {
        $data = Industry::findOrFail($id);
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

        Industry::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }

    // Delete
    public function delete($id)
    {
        $data = Industry::findOrFail($id);
        $data->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }

    public function export()
    {
        return Excel::download(new IndustryExport, 'Industry.xlsx');
    }
}
