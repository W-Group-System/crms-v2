<?php

namespace App\Http\Controllers;

use App\ProductApplication;
use App\ProductSubcategories;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProductSubcategoriesController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $subcategories = ProductSubcategories::with('application')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $productapp = ProductApplication::get();

        return view('product_subcategories.index', 
            array(
                'subcategories' => $subcategories,
                'productapp' => $productapp,
                'search' => $request->search
            )
        ); 
    }

    // Store
    public function store(Request $request) 
    {
        $productSubCategories = new ProductSubcategories;
        $productSubCategories->ProductApplicationId = $request->ProductApplicationId;
        $productSubCategories->Name = $request->Name;
        $productSubCategories->Description = $request->Description;
        $productSubCategories->save();

        Alert::success('Successfully Saved.')->persistent('Dismiss');
        return back();
    }

    // Edit
    // public function edit($id)
    // {
    //     if(request()->ajax())
    //     {
    //         $data = ProductSubcategories::findOrFail($id);
    //         return response()->json(['data' => $data]);
    //     }
    // }

    // Update
    public function update(Request $request, $id)
    {
        $productSubCategories = ProductSubcategories::findOrFail($id);
        $productSubCategories->ProductApplicationId = $request->ProductApplicationId;
        $productSubCategories->Name = $request->Name;
        $productSubCategories->Description = $request->Description;
        $productSubCategories->save();

        Alert::success('Successfully updated.')->persistent('Dismiss');
        return back();
    }

    // Delete
    public function delete(Request $request)
    {
        $data = ProductSubcategories::findOrFail($request->id);
        $data->delete();

        return array('message' => 'Successfully Deleted');
    }
}
