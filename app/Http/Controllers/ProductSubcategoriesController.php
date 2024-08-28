<?php

namespace App\Http\Controllers;

use App\Exports\ApplicationSubCategoriesExport;
use App\ProductApplication;
use App\ProductSubcategories;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class ProductSubcategoriesController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $fetchAll = $request->input('fetch_all', false); // Get the fetch_all parameter

        $productapp = ProductApplication::get();

        $subcategories = ProductSubcategories::with('application')
            ->when($request->search, function($q)use($request){
                $q->whereHas('application', function($q)use($request) {
                    $q->where('Name', 'LIKE', '%'.$request->search.'%');
                })
                ->orWhere('Name', "LIKE", "%".$request->search."%")
                ->orWhere('Description', 'LIKE', '%'.$request->search.'%');
            })
            ->orderBy('id', 'desc');


        if ($fetchAll)
        {
            $subcategories = $subcategories->get();
            return response()->json($subcategories);
        }
        else
        {
            $subcategories = $subcategories->paginate($request->entries ?? 10);
            return view('product_subcategories.index', 
                array(
                    'subcategories' => $subcategories,
                    'productapp' => $productapp,
                    'search' => $request->search,
                    'entries' => $request->entries
                )
            ); 
        }
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
    public function edit($id)
    {
        $data = ProductSubcategories::findOrFail($id);
        return response()->json(['data' => $data]);
    }

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

    public function export()
    {
        return Excel::download(new ApplicationSubCategoriesExport, 'Application Sub Categories.xlsx');
    }
}
