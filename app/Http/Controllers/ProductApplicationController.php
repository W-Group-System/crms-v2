<?php

namespace App\Http\Controllers;
use App\ProductApplication;
use Validator;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProductApplicationController extends Controller
{
    // List 
    public function index(Request $request)
    {   
        $productApplications = ProductApplication::when($request->search, function($q)use($request) {
            $q->where('Name', 'LIKE', "%".$request->search."%")->orWhere('Description', 'LIKE', "%".$request->search."%");
        })
        ->orderBy('id', 'desc')
        ->paginate(10);
        
        return view('product_applications.index', 
            array(
                'productApplications' => $productApplications,
                'search' => $request->search
            )
        ); 
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
    // public function edit($id)
    // {
    //     if(request()->ajax())
    //     {
    //         $data = ProductApplication::findOrFail($id);
    //         return response()->json(['data' => $data]);
    //     }
    // }

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
}
