<?php

namespace App\Http\Controllers;

use App\Exports\ProductApplicationExport;
use App\ProductApplication;
use Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class ProductApplicationController extends Controller
{
    // List 
    public function index(Request $request)
    {   
        $fetchAll = $request->input('fetch_all', false); 

        $productApplications = ProductApplication::when($request->search, function($q)use($request) {
            $q->where('Name', 'LIKE', "%".$request->search."%")->orWhere('Description', 'LIKE', "%".$request->search."%");
        })
        ->orderBy('id', 'desc');

        if ($fetchAll)
        {
            $productApplications = $productApplications->get();
            return response()->json($productApplications);
        }
        else
        {
            $productApplications = $productApplications->paginate($request->entries ?? 10);
            
            return view('product_applications.index', 
                array(
                    'productApplications' => $productApplications,
                    'search' => $request->search,
                    'entries' => $request->entries
                )
            ); 
        }
    }

    // Store
    public function store(Request $request) 
    {
        $productApplication = new ProductApplication;
        $productApplication->Name = $request->Name;
        $productApplication->Description = $request->Description;
        $productApplication->save();

        Alert::success('Successfully Saved.')->persistent('Dismiss');
        return back();
    }

    // Edit
    public function edit($id)
    {
        $data = ProductApplication::findOrFail($id);
        return response()->json(['data' => $data]);
    }

    // Update
    public function update(Request $request, $id)
    {
        $productApplication = ProductApplication::findOrFail($id);
        $productApplication->Name = $request->Name;
        $productApplication->Description = $request->Description;
        $productApplication->save();

        Alert::success('Successfully Updated.')->persistent('Dismiss');
        return back();
    }

    // Delete
    public function delete(Request $request)
    {
        $data = ProductApplication::findOrFail($request->id);
        $data->delete();

        return array('message' => 'Successfully Deleted');
    }

    public function export()
    {
        return Excel::download(new ProductApplicationExport, 'Product Application.xlsx');
    }
}
