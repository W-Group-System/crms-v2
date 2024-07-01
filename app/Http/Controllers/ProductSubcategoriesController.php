<?php

namespace App\Http\Controllers;

use App\ProductApplication;
use App\ProductSubcategories;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProductSubcategoriesController extends Controller
{
    // List
    public function index()
    {   
        $subcategories = ProductSubcategories::with('application')->orderBy('id', 'desc')->get();
        $productapp = ProductApplication::get();
        if(request()->ajax())
        // dd(request());
        {
            return datatables()->of($subcategories)
                    ->addColumn('action', function($data){
                        $buttons = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary">Edit</button>';
                        $buttons .= '&nbsp;&nbsp;';
                        $buttons .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger">Delete</button>';
                        return $buttons;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('product_subcategories.index', compact('subcategories', 'productapp')); 
    }

    // Store
    public function store(Request $request) 
    {
        $rules = array(
            'ProductApplicationId'  =>  'required',
            'Name'                  =>  'required',
            'Description'           =>  'required'
        );

        $customMessages = [
            'ProductApplicationId.required' => 'The product application field is required.',
            'Name.required'                 => 'The subcategory field is required.',
            'Description.required'          => 'The description field is required.',
        ];
    
        $error = Validator::make($request->all(), $rules, $customMessages);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'ProductApplicationId'  =>  $request->ProductApplicationId,
            'Name'                  =>  $request->Name,
            'Description'           =>  $request->Description
        );

        ProductSubcategories::create($form_data);

        return response()->json(['success' => 'Data Added Successfully.']);
    }

    // Edit
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = ProductSubcategories::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $rules = array(
            'ProductApplicationId'  =>  'required',
            'Name'                  =>  'required',
            'Description'           =>  'required'
        );

        $customMessages = [
            'ProductApplicationId.required' => 'The product application field is required.',
            'Name.required'                 => 'The subcategory field is required.',
            'Description.required'          => 'The description field is required.',
        ];
    
        $error = Validator::make($request->all(), $rules, $customMessages);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'ProductApplicationId'  =>  $request->ProductApplicationId,
            'Name'                  =>  $request->Name,
            'Description'           =>  $request->Description
        );

        ProductSubcategories::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }

    // Delete
    public function delete($id)
    {
        $data = ProductSubcategories::findOrFail($id);
        $data->delete();
    }
}
