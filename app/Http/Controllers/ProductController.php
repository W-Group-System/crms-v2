<?php

namespace App\Http\Controllers;

use App\BasePrice;
use App\Client;
use App\Helpers\Helpers;
use App\Product;
use App\User;
use App\ProductApplication;
use App\ProductFiles;
use App\ProductMaterialsComposition;
use App\ProductRawMaterials;
use App\ProductSpecification;
use App\ProductSubcategories;
use App\RawMaterial;
use Validator;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    // Current List
    public function current(Request $request)
    {   
        $products = Product::with(['userById', 'userByUserId'])
            ->when($request->search, function($q)use($request){
                $q->where('ddw_number', "LIKE" ,"%".$request->search."%")->orWhere('code', "LIKE", "%".$request->search."%");
            })
            ->when($request->application_filter, function($q)use($request) {
                $q->where('application_id', $request->application_filter);
            })
            ->when($request->material_filter, function($q)use($request) {
                $q->whereHas('productMaterialComposition', function($q)use($request) {
                    $q->where('MaterialId', $request->material_filter);
                });
            }) 
            ->where('status', '4')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        
        $application = ProductApplication::get();
        $raw_material = RawMaterial::get();
        
        return view('products.current',
            array(
                'products' => $products,
                'search' => $request->search,
                'application' =>  $application,
                'raw_material' => $raw_material,
                'application_filter' => $request->application_filter,
                'material_filter' => $request->material_filter
            )
        ); 
    }

    // New List
    public function new(Request $request)
    {   
        $products = Product::with(['userById', 'userByUserId'])
            ->when($request->search, function($q)use($request){
                $q->where('ddw_number', "LIKE" ,"%".$request->search."%")->orWhere('code', "LIKE", "%".$request->search."%");
            }) 
            ->where('status', '2')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $product_applications = ProductApplication::all();
        $product_subcategories = ProductSubcategories::all();
        
        return view('products.new',
            array(
                'products' => $products,
                'search' => $request->search,
                'product_applications' => $product_applications,
                'product_subcategories' => $product_subcategories
            )
        ); 
    }

    // Draft List 
    public function draft(Request $request)
    {  
        $search = $request->search;

        $products = Product::with(['userById', 'userByUserId'])->where('status', '1')
            ->when($request->search, function($q)use($request) {
                $q->where('ddw_number', "LIKE" ,"%".$request->search."%")->orWhere('code', "LIKE", "%".$request->search."%");
            })
            ->orWhere('code', $request->search)
            ->orderBy('id', 'desc')
            ->paginate(10);

        $product_applications = ProductApplication::all();
        $product_subcategories = ProductSubcategories::all();

        return view('products.draft', compact('products','product_applications', 'product_subcategories', 'search')); 
    }

    // Archived List
    public function archived(Request $request)
    {   
        $products = Product::with(['userById', 'userByUserId'])
            ->when($request->search, function($q)use($request) {
                $q->where('ddw_number', "LIKE" ,"%".$request->search."%")->orWhere('code', "LIKE", "%".$request->search."%");
            })
            ->where('status', '5')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        
        return view('products.archived', 
            array(
                'products' => $products,
                'search' => $request->search
            )
        ); 
    }

    // Store
    public function store(Request $request)
    {
        $rules = array(
            'code'              =>  'required',
            'type'              =>  'required',
            'application_id'    =>  'required'
        );

        $customMessages = [
            'application_id.required'   =>  'The application field is required.',
            'code.required'             =>  'The product code field is required'            
        ];

        $error = Validator::make($request->all(), $rules, $customMessages);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'ddw_number'                    =>  $request->ddw_number,
            'code'                          =>  $request->code,
            'reference_no'                  =>  $request->reference_no,
            'type'                          =>  $request->type,
            'application_id'                =>  $request->application_id,
            'application_subcategory_id'    =>  $request->application_subcategory_id,
            'product_origin'                =>  $request->product_origin,
            'created_by'                    =>  auth()->user()->id,
            'status'                        =>  '1'
        );

        Product::create($form_data);

        // return response()->json(['success' => 'Data Added Successfully.']);
        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    // Edit
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = Product::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->ddw_number = $request->ddw_number;
        $product->code = $request->code;
        $product->reference_no = $request->reference_no;
        $product->type = $request->type;
        $product->application_id = $request->application_id;
        $product->application_subcategory_id = $request->application_subcategory_id;
        $product->product_origin = $request->product_origin;
        $product->save();

        Alert::success('Successfully Updated.')->persistent('Dismiss');
        return back();
    }

    // View
    public function view($id)
    {
        $data = Product::with([
            'productMaterialComposition', 
            'productSpecification' => function($q) {
                $q->orderBy('id', 'desc');
            },
            'productFiles' => function($q) {
                $q->orderBy('id', 'desc');
            },
            'productDataSheet' => function($q) {
                $q->orderBy('id', 'desc');
            },
            'productEventLogs'
            ])
            ->find($id);

        $users = User::all();
        
        $product_applications = ProductApplication::find($data->application_id);
        $product_subcategories = ProductSubcategories::find($data->application_subcategory_id);
        $userAccounts = $users->firstWhere('user_id', $data->created_by) ?? $users->firstWhere('id', $data->created_by);
        $approveUsers = $users->firstWhere('user_id', $data->approved_by) ?? $users->firstWhere('id', $data->approved_by);

        $rawMaterials = RawMaterial::where('status', 'Active')->get();
        $client = Client::get();

        return view('products.view', compact('data', 'product_applications', 'product_subcategories', 'userAccounts', 'approveUsers', 'rawMaterials', 'client'));
    }

    // Delete
    public function delete(Request $request)
    {
        $data = Product::findOrFail($request->id);
        $data->delete();

        return array('message' => 'Successfully Deleted.');
    }

    public function updateRawMaterials(Request $request, $id)
    {
        $product_raw_materials = ProductMaterialsComposition::where('ProductId', $id)->delete();

        foreach($request->raw_materials as $key=>$rm)
        {   
            $product_raw_materials = new ProductMaterialsComposition;
            $product_raw_materials->MaterialId = $rm;
            $product_raw_materials->Percentage = $request->percent[$key];
            $product_raw_materials->ProductId = $id;
            $product_raw_materials->save();
        }

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function addToNewProducts(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->status = 2;
        $product->save();

        return array('message' => 'Successfully added to new products');
    }

    public function addToCurrentProducts(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->status = 4;
        $product->save();

        return array('message' => 'Successfully added to current products');
    }

    public function addToDraftProducts(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->status = 1;
        $product->save();

        return array('message' => 'Successfully added to draft products');
    }

    public function addToArchiveProducts(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->status = 5;
        $product->save();

        return array('message' => 'Successfully added to archive products');
    }

    public function specification(Request $request)
    {
        $productSpecification = new ProductSpecification;
        $productSpecification->Parameter = $request->parameter;
        $productSpecification->Specification = $request->specification;
        $productSpecification->TestingCondition = $request->testing_condition;
        $productSpecification->Remarks = $request->remarks;
        $productSpecification->ProductId = $request->product_id;
        $productSpecification->CreatedDate = date('Y-m-d h:i:s');
        $productSpecification->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function editSpecification(Request $request, $id)
    {
        $productSpecification = ProductSpecification::findOrFail($id);
        $productSpecification->Parameter = $request->parameter;
        $productSpecification->Specification = $request->specification;
        $productSpecification->TestingCondition = $request->testing_condition;
        $productSpecification->Remarks = $request->remarks;
        $productSpecification->ModifiedDate = date('Y-m-d h:i:s');
        $productSpecification->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function addFiles(Request $request)
    {
        $fileProducts = new ProductFiles;
        $fileProducts->ProductId = $request->product_id;
        $fileProducts->Name = $request->name;
        $fileProducts->Description = $request->description;
        $fileProducts->ClientId = $request->client;
        $fileProducts->IsConfidential = isset($request->is_confidential)?1:0;
        
        $file = $request->file('file');
        $fileName = time().'_'.$file->getClientOriginalName();
        $file->move(public_path().'/attachments/', $fileName);
        
        $fileProducts->Path = "/attachments/" . $fileName;
        $fileProducts->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function editFiles(Request $request, $id)
    {
        $fileProducts = ProductFiles::findOrFail($id);
        $fileProducts->Name = $request->name;
        $fileProducts->Description = $request->description;
        $fileProducts->ClientId = $request->client;
        $fileProducts->IsConfidential = isset($request->is_confidential)?1:0;
        
        if ($request->hasFile('file'))
        {
            $file = $request->file('file');
            $fileName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path().'/attachments/', $fileName);

            $fileProducts->Path = "/attachments/" . $fileName;
        }
        
        $fileProducts->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }
}
