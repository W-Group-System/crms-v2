<?php

namespace App\Http\Controllers;

use App\BasePrice;
use App\Client;
use App\Exports\CurrentProductExport;
use App\Exports\ArchiveExport;
use App\Exports\DraftProductExport;
use App\Exports\NewProductExport;
use App\Helpers\Helpers;
use App\Product;
use App\ProductAllergens;
use App\User;
use App\ProductApplication;
use App\ProductDataSheet;
use App\ProductFiles;
use App\ProductHeavyMetal;
use App\ProductMaterialsComposition;
use App\ProductMicrobiologicalAnalysis;
use App\ProductNutrionalInformation;
use App\ProductPhysicoChemicalAnalyses;
use App\ProductPotentialBenefit;
use App\ProductRawMaterials;
use App\ProductSpecification;
use App\ProductSubcategories;
use App\RawMaterial;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
class ProductController extends Controller
{
    // Current List
    public function current(Request $request)
    {   
        $products = Product::with(['userById', 'userByUserId'])
            ->where('status', 4)
            ->where(function($q)use($request){
                if ($request->search != null)
                {
                    $q->where('ddw_number', "LIKE" ,"%".$request->search."%")
                    ->orWhere('code', "LIKE", "%".$request->search."%")
                    ->orWhereHas('userByUserId', function($q)use($request) {
                        $q->where('full_name', 'LIKE', "%".$request->search."%");
                    })
                    ->orWhereHas('userById', function($q)use($request) {
                        $q->where('full_name', 'LIKE', "%".$request->search."%");
                    });
                }
            })
            ->when($request->application_filter, function($q)use($request) {
                $q->where('application_id', $request->application_filter);
            })
            ->when($request->material_filter, function($q)use($request) {
                $q->whereHas('productMaterialComposition', function($q)use($request) {
                    $q->where('MaterialId', $request->material_filter);
                });
            }) 
            ->orderBy('updated_at', 'desc')
            ->paginate($request->entries ?? 10);
        
        $application = ProductApplication::get();
        $raw_material = RawMaterial::get();
        
        return view('products.current',
            array(
                'products' => $products,
                'search' => $request->search,
                'application' =>  $application,
                'raw_material' => $raw_material,
                'application_filter' => $request->application_filter,
                'material_filter' => $request->material_filter,
                'entries' => $request->entries
            )
        ); 
    }

    // New List
    public function new(Request $request)
    {   
        $products = Product::with(['userById', 'userByUserId'])
            ->where('status', 2)
            ->where(function($q)use($request){
                if ($request->search != null)
                {
                    $q->where('ddw_number', "LIKE" ,"%".$request->search."%")
                    ->orWhere('code', "LIKE", "%".$request->search."%")
                    ->orWhereHas('userByUserId', function($q)use($request) {
                        $q->where('full_name', 'LIKE', "%".$request->search."%");
                    })
                    ->orWhereHas('userById', function($q)use($request) {
                        $q->where('full_name', 'LIKE', "%".$request->search."%");
                    });
                }
            }) 
            ->orderBy('updated_at', 'desc')
            ->paginate($request->entries ?? 10);

        $product_applications = ProductApplication::all();
        $product_subcategories = ProductSubcategories::all();
        
        return view('products.new',
            array(
                'products' => $products,
                'search' => $request->search,
                'product_applications' => $product_applications,
                'product_subcategories' => $product_subcategories,
                'entries' => $request->entries
            )
        ); 
    }

    // Draft List 
    public function draft(Request $request)
    {  
        $search = $request->search;

        $products = Product::where('status', 1)
            ->with(['userById', 'userByUserId'])
            ->where(function($q)use($request) {
                if ($request->search != null)
                {
                    $q->where('ddw_number', "LIKE" ,"%".$request->search."%")
                    ->orWhere('code', "LIKE", "%".$request->search."%")
                    ->orWhereHas('userByUserId', function($q)use($request) {
                        $q->where('full_name', 'LIKE', "%".$request->search."%");
                    })
                    ->orWhereHas('userById', function($q)use($request) {
                        $q->where('full_name', 'LIKE', "%".$request->search."%");
                    });
                }
            })
            ->orderBy('id', 'desc')
            ->paginate($request->entries ?? 10);

        $product_applications = ProductApplication::all();
        $product_subcategories = ProductSubcategories::all();
        $entries = $request->entries;

        return view('products.draft', compact('products','product_applications', 'product_subcategories', 'search', 'entries')); 
    }

    // Archived List
    public function archived(Request $request)
    {   
        $products = Product::with(['userById', 'userByUserId'])
            ->where('status', 5)
            ->where(function($q)use($request) {
                if ($request->search != null)
                {
                    $q->where('ddw_number', "LIKE" ,"%".$request->search."%")
                    ->orWhere('code', "LIKE", "%".$request->search."%")
                    ->orWhereHas('userByUserId', function($q)use($request) {
                        $q->where('full_name', 'LIKE', "%".$request->search."%");
                    })
                    ->orWhereHas('userById', function($q)use($request) {
                        $q->where('full_name', 'LIKE', "%".$request->search."%");
                    });
                }
            })
            ->orderBy('updated_at', 'desc')
            ->paginate($request->entries ?? 10);
        
        return view('products.archived', 
            array(
                'products' => $products,
                'search' => $request->search,
                'entries' => $request->entries
            )
        ); 
    }

    // Store
    public function store(Request $request)
    {
        $series_number = 0;
        if(preg_match('/^(\D+)\s+([\dA-Za-z]+)$/', $request->code, $matches))
        {
            $series_number = $matches[2];
        }

        if ($series_number == 0)
        {
            $product = new Product;
            $product->ddw_number = $request->ddw_number;
            $product->code = $request->code;
            $product->reference_no = $request->reference_no;
            $product->type = $request->type;
            $product->application_id = $request->application_id;
            $product->application_subcategory_id = $request->application_subcategory_id;
            $product->product_origin = $request->product_origin;
            $product->created_by = auth()->user()->id;
            $product->status = 1;
            $product->approved_by = null;
            $product->save();

            productManagementLogs("create", $product->code);
            return response()->json(['status' => 1, 'message' => 'Successfully Saved.']);
        }
        else
        {
            $products = Product::where('code', 'LIKE', '%'.$series_number.'%')->first();
            
            if ($products == null)
            {
                $product = new Product;
                $product->ddw_number = $request->ddw_number;
                $product->code = $request->code;
                $product->reference_no = $request->reference_no;
                $product->type = $request->type;
                $product->application_id = $request->application_id;
                $product->application_subcategory_id = $request->application_subcategory_id;
                $product->product_origin = $request->product_origin;
                $product->created_by = auth()->user()->id;
                $product->status = 1;
                $product->save();
    
                productManagementLogs("create", $product->code);
                return response()->json(['status' => 1, 'message' => 'Successfully Saved.']);
            }
            else
            {
                return response()->json(['status' => 0, 'error' => 'The number series is existing.']);
            }
        }
        
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
        $series_number = 0;
        if(preg_match('/^(\D+)\s+([\dA-Za-z]+)$/', $request->code, $matches))
        {
            $series_number = $matches[2];
        }

        if($series_number == 0)
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

            productManagementLogs("update", $product->code);
            return response()->json(['status' => 1, 'message' => 'Successfully Saved.']);
        }
        else
        {
            $products = Product::where('code', 'LIKE', '%'.$series_number.'%')->first();
    
            if ($products == null)
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
    
                productManagementLogs("update", $product->code);
                return response()->json(['status' => 1, 'message' => 'Successfully Saved.']);
            }
            else
            {
                return response()->json(['status' => 0, 'error' => 'The number series is existing.']);
            }
        }

        // Alert::success('Successfully Updated.')->persistent('Dismiss');
        // return back();
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
            'productDataSheet.productPhysicoChemicalAnalyses' => function($q) {
                $q->orderBy('id', 'desc');
            },
            'productDataSheet.productMicrobiologicalAnalysis',
            'productDataSheet.productHeavyMetal',
            'productDataSheet.productNutritionalInformation',
            'productDataSheet.productAllergens',
            'productDataSheet.productPotentialBenefit',
            'productEventLogs'
            ])
            ->find($id);

        $users = User::all();
        
        $product_applications = ProductApplication::find($data->application_id);
        $product_subcategories = ProductSubcategories::find($data->application_subcategory_id);
        $userAccounts = $users->firstWhere('user_id', $data->created_by) ?? $users->firstWhere('id', $data->created_by);
        
        $rawMaterials = RawMaterial::where('status', 'Active')->get();
        $client = Client::get();

        return view('products.view', compact('data', 'product_applications', 'product_subcategories', 'userAccounts', 'rawMaterials', 'client'));
    }

    // Delete
    public function delete(Request $request)
    {
        $data = Product::findOrFail($request->id);
        $data->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }

    public function updateRawMaterials(Request $request, $id)
    {
        $product_composition = ProductMaterialsComposition::where('ProductId', $id)->delete();
        
        foreach($request->percentage as $key=>$value)
        {
            if (is_null($request->percentage[$key])) {
                continue;
            }

            $product_composition = new ProductMaterialsComposition;
            $product_composition->MaterialId = $request->raw_materials[$key];
            $product_composition->Percentage = $value;
            $product_composition->ProductId = $id;
            $product_composition->save(); 
            
            productManagementLogs("update_raw_materials", $product_composition->products->code);
        }

        // $validator = Validator::make($request->all(), [
        //     'raw_materials' => 'array|min:1',
        //     'raw_materials.*' => 'distinct'
        // ], [
        //     'raw_materials.*.distinct' => 'The raw materials should be unique.',
        // ]);

        // if ($validator->fails())
        // {
        //     $msg = $validator->errors()->first();
        //     Alert::error($msg)->persistent('Dismiss');
        // }
        // else
        // {
        //     $product_raw_materials = ProductMaterialsComposition::where('ProductId', $id)->delete();
    
        //     if($request->raw_materials != null)
        //     {
        //         foreach($request->raw_materials as $key=>$rm)
        //         {   
        //             $product_raw_materials = new ProductMaterialsComposition;
        //             $product_raw_materials->MaterialId = $rm;
        //             $product_raw_materials->Percentage = $request->percent[$key];
        //             $product_raw_materials->ProductId = $id;
        //             $product_raw_materials->save();
        //         }

                // Alert::success('Successfully Saved')->persistent('Dismiss');
        //     }
        //     else
        //     {
        //         Alert::error('Error! You must add raw materials')->persistent('Dismiss');
        //     }
    
        // }

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back()->with(['tab' => 'materials']);
    }

    public function addToNewProducts(Request $request)
    {
        $productMaterialComposition = ProductMaterialsComposition::where('ProductId', $request->id)->get();

        if ($productMaterialComposition->isEmpty())
        {
            Alert::error('Error! Unable to move products into current');
            return back();
        }
        
        $product = Product::findOrFail($request->id);
        $product->status = 2;
        $product->save();

        productManagementLogs("move_to_new", $product->code);

        Alert::success('Successfully added to new products')->persistent("Dismiss");
        return redirect('/new_products');
    }

    public function addToCurrentProducts(Request $request)
    {
        $productMaterialComposition = ProductMaterialsComposition::where('ProductId', $request->id)->get();

        if ($productMaterialComposition->isEmpty())
        {
            Alert::error('Error! Unable to move products into current');
            return back();
        }

        $product = Product::findOrFail($request->id);
        $product->status = 4;
        $product->approved_by = auth()->user()->id;
        $product->date_approved = date('Y-m-d h:i:s');
        $product->save();

        productManagementLogs("move_to_current", $product->code);

        Alert::success('Successfully added to current products')->persistent("Dismiss");
        return redirect('/current_products');
    }

    public function addToDraftProducts(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->status = 1;
        $product->save();

        productManagementLogs($request->action, $product->code);

        Alert::success('Successfully added to draft products')->persistent("Dismiss");
        return redirect('/draft_products');
    }

    public function addToArchiveProducts(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->status = 5;
        $product->save();

        productManagementLogs("archive", $product->code);

        Alert::success('Sucessfully Archived')->persistent('Dismiss');
        return redirect('/archived_products');
    }

    public function specification(Request $request)
    {
        // dd($request->all());
        $productSpecification = ProductSpecification::where('ProductId', $request->product_id)->where('Parameter', $request->parameter)->first();
        
        if (!empty($productSpecification))
        {
            Alert::error('Error! Entry already exist')->persistent('Dismiss');
        }
        else
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
        }
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

    public function deleteSpecification($id)
    {
        $productSpecification = ProductSpecification::findOrFail($id);
        $productSpecification->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }

    public function addFiles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'max:1024'
        ], [
            'file*' => 'The file may not be greater than 1MB.'
        ]);

        if ($validator->fails())
        {
            $errors = $validator->errors();
            
            return back()->with(['errors' => $errors, 'tab' => 'files']);
        }

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

        productManagementLogs("create_files", $fileProducts->product->code);

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back()->with(['tab' => 'files']);
    }

    public function editFiles(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'max:1024'
        ], [
            'file*' => 'The file may not be greater than 1MB.'
        ]);

        if ($validator->fails())
        {
            $errors = $validator->errors();
            
            return back()->with(['errors' => $errors, 'tab' => 'files']);
        }

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

        productManagementLogs("update_files", $fileProducts->product->code);

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back()->with(['tab' => 'files']);
    }

    public function deleteProductFiles($id)
    {
        $productFiles = ProductFiles::findOrFail($id);
        $productFiles->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back()->with(['tab' => 'files']);
    }

    public function productDs(Request $request)
    {
        $productDataSheet = ProductDataSheet::where('ProductId', $request->product_id)->first();
        
        if(!empty($productDataSheet))
        {
            Alert::error('Error! The product data sheet is existing')->persistent('Dismiss');
            return back();
        }
        else
        {
            $productDataSheet = new ProductDataSheet;
            $productDataSheet->ProductId = $request->product_id;
            $productDataSheet->CompanyId = $request->company;
            $productDataSheet->ControlNumber = $request->control_number;
            $productDataSheet->DateIssued = $request->date_issued;
            $productDataSheet->Description = $request->description;
            $productDataSheet->Description2 = $request->description2;
            $productDataSheet->Appearance = $request->appearance;
            $productDataSheet->Application = $request->application;
            $productDataSheet->DirectionForUse = $request->direction_for_use;
            $productDataSheet->Storage = $request->storage;
            $productDataSheet->TechnicalAssistance = $request->technical_assistance;
            $productDataSheet->PurityAndLegalStatus = $request->purity_and_legal_status;
            $productDataSheet->Packaging = $request->packaging;
            $productDataSheet->Certification = $request->certifications;
            $productDataSheet->save();
            
            // $productPotentialBenefits = ProductPotentialBenefit::where('ProductDataSheetId', $id)->delete();
            if ($request->potentialBenefit)
            {
                foreach($request->potentialBenefit as $pb)
                {
                    $productPotentialBenefits = new ProductPotentialBenefit;
                    $productPotentialBenefits->ProductDataSheetId = $productDataSheet->Id;
                    $productPotentialBenefits->Benefit = $pb;
                    $productPotentialBenefits->save();
                }
            }

            if ($request->pcaParameter)
            {
                // $productPca = ProductPhysicoChemicalAnalyses::where('ProductDataSheetId', $id)->delete();
                foreach($request->pcaParameter as $key => $pca)
                {
                    $productPca = new ProductPhysicoChemicalAnalyses;
                    $productPca->ProductDataSheetId = $productDataSheet->Id;
                    $productPca->Parameter = $pca;
                    $productPca->Value = $request->pcaValue[$key];
                    $productPca->Remarks = $request->pcaRemark[$key];
                    $productPca->save();
                }
            }
            
            if($request->maParameter)
            {
                // $productMa = ProductMicrobiologicalAnalysis::where('ProductDataSheetId', $id)->delete();
                foreach($request->maParameter as $key=>$maParameter)
                {
                    $productMa = new ProductMicrobiologicalAnalysis;
                    $productMa->ProductDataSheetId = $productDataSheet->Id;
                    $productMa->Parameter = $maParameter;
                    $productMa->Value = $request->maValue[$key];
                    $productMa->Remarks = $request->maRemark[$key];
                    $productMa->save();
                }
            }

            if ($request->heavyMetalsParameter)
            {
                // $productHeavyMetal = ProductHeavyMetal::where('ProductDataSheetId', $id)->delete();
                foreach($request->heavyMetalsParameter as $key=>$parameter)
                {
                    $productHeavyMetal = new ProductHeavyMetal;
                    $productHeavyMetal->ProductDataSheetId = $productDataSheet->Id;
                    $productHeavyMetal->Parameter = $parameter;
                    $productHeavyMetal->Value = $request->heavyMetalsValue[$key];
                    $productHeavyMetal->save();
                }
            }

            if ($request->nutrionalInfoValue)
            {
                // $productNutrionalInfo = ProductNutrionalInformation::where('ProductDataSheetId', $id)->delete();
                foreach($request->nutrionalInfoParameter as $key=>$parameter)
                {
                    $productNutrionalInfo = new ProductNutrionalInformation;
                    $productNutrionalInfo->ProductDataSheetId = $productDataSheet->Id;
                    $productNutrionalInfo->Parameter = $parameter;
                    $productNutrionalInfo->Value = $request->nutrionalInfoValue[$key];
                    $productNutrionalInfo->save();
                }
            }

            if ($request->allergensParameter)
            {
                // $allergens = ProductAllergens::where('ProductDataSheetId', $id)->delete();
                foreach($request->allergensParameter as $key=>$parameter)
                {
                    $allergens = new ProductAllergens;
                    $allergens->ProductDataSheetId = $productDataSheet->Id;
                    $allergens->Parameter = $parameter;
                    $allergens->IsAllergen = isset($request->isAllergen[$key]) ? 1 : 0;
                    $allergens->save();
                }
            }

            Alert::success('Successfully Saved')->persistent('Dismiss');
            return back()->with(['tab' => 'pds']);
        }
    }

    public function updatePds(Request $request, $id)
    {
        $productDataSheet = ProductDataSheet::findOrFail($id);
        $productDataSheet->CompanyId = $request->company;
        $productDataSheet->ControlNumber = $request->control_number;
        $productDataSheet->DateIssued = $request->date_issued;
        $productDataSheet->Description = $request->description;
        $productDataSheet->Description2 = $request->description2;
        $productDataSheet->Appearance = $request->appearance;
        $productDataSheet->Application = $request->application;

        $productPotentialBenefits = ProductPotentialBenefit::where('ProductDataSheetId', $id)->delete();
        if ($request->potentialBenefit)
        {
            foreach($request->potentialBenefit as $pb)
            {
                $productPotentialBenefits = new ProductPotentialBenefit;
                $productPotentialBenefits->ProductDataSheetId = $id;
                $productPotentialBenefits->Benefit = $pb;
                $productPotentialBenefits->save();
            }
        }

        if ($request->pcaParameter)
        {
            $productPca = ProductPhysicoChemicalAnalyses::where('ProductDataSheetId', $id)->delete();
            foreach($request->pcaParameter as $key => $pca)
            {
                $productPca = new ProductPhysicoChemicalAnalyses;
                $productPca->ProductDataSheetId = $id;
                $productPca->Parameter = $pca;
                $productPca->Value = $request->pcaValue[$key];
                $productPca->Remarks = $request->pcaRemark[$key];
                $productPca->save();
            }
        }
        
        if($request->maParameter)
        {
            $productMa = ProductMicrobiologicalAnalysis::where('ProductDataSheetId', $id)->delete();
            foreach($request->maParameter as $key=>$maParameter)
            {
                $productMa = new ProductMicrobiologicalAnalysis;
                $productMa->ProductDataSheetId = $id;
                $productMa->Parameter = $maParameter;
                $productMa->Value = $request->maValue[$key];
                $productMa->Remarks = $request->maRemark[$key];
                $productMa->save();
            }
        }

        if ($request->heavyMetalsParameter)
        {
            $productHeavyMetal = ProductHeavyMetal::where('ProductDataSheetId', $id)->delete();
            foreach($request->heavyMetalsParameter as $key=>$parameter)
            {
                $productHeavyMetal = new ProductHeavyMetal;
                $productHeavyMetal->ProductDataSheetId = $id;
                $productHeavyMetal->Parameter = $parameter;
                $productHeavyMetal->Value = $request->heavyMetalsValue[$key];
                $productHeavyMetal->save();
            }
        }

        if ($request->nutrionalInfoValue)
        {
            $productNutrionalInfo = ProductNutrionalInformation::where('ProductDataSheetId', $id)->delete();
            foreach($request->nutrionalInfoParameter as $key=>$parameter)
            {
                $productNutrionalInfo = new ProductNutrionalInformation;
                $productNutrionalInfo->ProductDataSheetId = $id;
                $productNutrionalInfo->Parameter = $parameter;
                $productNutrionalInfo->Value = $request->nutrionalInfoValue[$key];
                $productNutrionalInfo->save();
            }
        }

        if ($request->allergensParameter)
        {
            $allergens = ProductAllergens::where('ProductDataSheetId', $id)->delete();
            foreach($request->allergensParameter as $key=>$parameter)
            {
                $allergens = new ProductAllergens;
                $allergens->ProductDataSheetId = $id;
                $allergens->Parameter = $parameter;
                $allergens->IsAllergen = isset($request->isAllergen[$key]) ? 1 : 0;
                $allergens->save();
            }
        }

        $productDataSheet->DirectionForUse = $request->direction_for_use;
        $productDataSheet->Storage = $request->storage;
        $productDataSheet->TechnicalAssistance = $request->technical_assistance;
        $productDataSheet->PurityAndLegalStatus = $request->purity_and_legal_status;
        $productDataSheet->Packaging = $request->packaging;
        $productDataSheet->Certification = $request->certifications;
        $productDataSheet->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back()->with(['tab' => 'pds']);
    }

    public function viewPdsDetails($id)
    {
        $productDataSheet = ProductDataSheet::with(['products', 'productPotentialBenefit', 'productPhysicoChemicalAnalyses', 'productMicrobiologicalAnalysis', 'productHeavyMetal', 'productNutritionalInformation', 'productAllergens'])->where('id', $id)->first();

        return view('products.pds_details', array('pds' => $productDataSheet));
    }

    public function deletePds($id)
    {
        $productDataSheet = ProductDataSheet::findOrFail($id);
        $productDataSheet->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back()->with(['tab' => 'pds']);
    }

    public function updateAllProductSpecification(Request $request)
    {
        $ps = ProductSpecification::where('ProductId', $request->product_id)->delete();

        if ($request->parameter)
        {
            foreach($request->parameter as $key=>$parameter)
            {
                $ps = new ProductSpecification;
                $ps->Parameter = $parameter;
                $ps->ProductId = $request->product_id;
                $ps->TestingCondition = $request->testing_condition[$key];
                $ps->Specification = $request->specification[$key];
                $ps->Remarks = $request->remarks[$key];
                $ps->save();
            }
        }

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function updateAllFiles(Request $request)
    {
        if ($request->has('files'))
        {
            $validator = Validator::make($request->all(), [
                'files[]' => 'max:1024|array'
            ], [
                'files*' => 'The file may not be greater than 1MB.'
            ]);
    
            if ($validator->fails())
            {
                $errors = $validator->errors();
                
                return back()->with(['errors' => $errors, 'tab' => 'files']);
            }

            $productFiles = ProductFiles::where('ProductId', $request->product_id)->delete();

            $attachments = $request->file('files');
            foreach($attachments as $key=>$attachment)
            {
                $productFiles = new ProductFiles;
                $productFiles->ProductId = $request->product_id;
                $productFiles->Name = $request->name[$key];
                $productFiles->ClientId = $request->client[$key];
                $productFiles->Description = $request->description[$key];

                $name = time().'-'.$attachment->getClientOriginalName();
                $attachment->move(public_path('attachments'), $name);

                $productFiles->path = '/attachments/'.$name;
                $productFiles->save();
            }

            Alert::success('Successfully Saved')->persistent('Dismiss');
            return back()->with(['tab' => 'files']);
        }
    }

    public function viewDraft($id)
    {
        $data = Product::with([
            'productMaterialComposition', 
            'productSpecification' => function($q) {
                $q->orderBy('id', 'desc');
            },
            'productFiles' => function($q) {
                $q->orderBy('id', 'desc');
            },
            'productDataSheet.productPhysicoChemicalAnalyses' => function($q) {
                $q->orderBy('id', 'desc');
            },
            'productDataSheet.productMicrobiologicalAnalysis',
            'productDataSheet.productHeavyMetal',
            'productDataSheet.productNutritionalInformation',
            'productDataSheet.productAllergens',
            'productDataSheet.productPotentialBenefit',
            'productEventLogs'
            ])
            ->find($id);

        $users = User::all();
        
        $product_applications = ProductApplication::find($data->application_id);
        $product_subcategories = ProductSubcategories::find($data->application_subcategory_id);
        $userAccounts = $users->firstWhere('user_id', $data->created_by) ?? $users->firstWhere('id', $data->created_by);
        // $approveUsers = $users->firstWhere('user_id', $data->approved_by) ?? $users->firstWhere('id', $data->approved_by);

        $rawMaterials = RawMaterial::get();
        $client = Client::get();

        return view('products.draft_view', compact('data', 'product_applications', 'product_subcategories', 'userAccounts', 'rawMaterials', 'client'));
    }
    
    public function viewNew($id)
    {
        $data = Product::with([
            'productMaterialComposition', 
            'productSpecification' => function($q) {
                $q->orderBy('id', 'desc');
            },
            'productFiles' => function($q) {
                $q->orderBy('id', 'desc');
            },
            'productDataSheet.productPhysicoChemicalAnalyses' => function($q) {
                $q->orderBy('id', 'desc');
            },
            'productDataSheet.productMicrobiologicalAnalysis',
            'productDataSheet.productHeavyMetal',
            'productDataSheet.productNutritionalInformation',
            'productDataSheet.productAllergens',
            'productDataSheet.productPotentialBenefit',
            'productEventLogs'
            ])
            ->find($id);

        $users = User::all();
        
        $product_applications = ProductApplication::find($data->application_id);
        $product_subcategories = ProductSubcategories::find($data->application_subcategory_id);
        $userAccounts = $users->firstWhere('user_id', $data->created_by) ?? $users->firstWhere('id', $data->created_by);

        $rawMaterials = RawMaterial::where('status', 'Active')->get();
        $client = Client::get();

        return view('products.new_view', compact('data', 'product_applications', 'product_subcategories', 'userAccounts', 'rawMaterials', 'client'));
    }

    public function viewArchived($id)
    {
        $data = Product::with([
            'productMaterialComposition', 
            'productSpecification' => function($q) {
                $q->orderBy('id', 'desc');
            },
            'productFiles' => function($q) {
                $q->orderBy('id', 'desc');
            },
            'productDataSheet.productPhysicoChemicalAnalyses' => function($q) {
                $q->orderBy('id', 'desc');
            },
            'productDataSheet.productMicrobiologicalAnalysis',
            'productDataSheet.productHeavyMetal',
            'productDataSheet.productNutritionalInformation',
            'productDataSheet.productAllergens',
            'productDataSheet.productPotentialBenefit',
            'productEventLogs'
            ])
            ->find($id);

        $users = User::all();
        
        $product_applications = ProductApplication::find($data->application_id);
        $product_subcategories = ProductSubcategories::find($data->application_subcategory_id);
        $userAccounts = $users->firstWhere('user_id', $data->created_by) ?? $users->firstWhere('id', $data->created_by);

        $rawMaterials = RawMaterial::where('status', 'Active')->get();
        $client = Client::get();

        return view('products.archived_view', compact('data', 'product_applications', 'product_subcategories', 'userAccounts', 'rawMaterials', 'client'));
    }

    public function exportCurrentProducts()
    {
        return Excel::download(new CurrentProductExport, 'Current Product.xlsx');
    }

    public function exportArchiveProducts()
    {
        return Excel::download(new ArchiveExport, 'Archived Product.xlsx');
    }

    public function exportNewProducts()
    {
        return Excel::download(new NewProductExport, 'New Product.xlsx');
    }

    public function exportDraftProducts()
    {
        return Excel::download(new DraftProductExport, 'Draft Product.xlsx');
    }

    public function salesProduct(Request $request)
    {
        $products = Product::with(['userById', 'userByUserId', 'application'])
            ->when($request->search, function($q)use($request){
                $q->where('ddw_number', "LIKE" ,"%".$request->search."%")
                    ->orWhere('code', "LIKE", "%".$request->search."%")
                    ->orWhereHas('userByUserId', function($q)use($request) {
                        $q->where('full_name', 'LIKE', "%".$request->search."%");
                    })
                    ->orWhereHas('userById', function($q)use($request) {
                        $q->where('full_name', 'LIKE', "%".$request->search."%");
                    });
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
            ->paginate($request->entries ?? 10);
        
        $application = ProductApplication::get();
        $raw_material = RawMaterial::get();
        
        return view('products.sales_product',
            array(
                'products' => $products,
                'search' => $request->search,
                'application' =>  $application,
                'raw_material' => $raw_material,
                'application_filter' => $request->application_filter,
                'material_filter' => $request->material_filter,
                'entries' => $request->entries
            )
        ); 
    }

    public function printMrdcPds($id)
    {
        $pds = ProductDataSheet::with('productPhysicoChemicalAnalyses', 'productMicrobiologicalAnalysis', 'productHeavyMetal', 'productNutritionalInformation', 'productAllergens', 'productPotentialBenefit')->findOrFail($id);

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('products.print_mrdc_pds',
            array(
                'pds' => $pds
            )
        );
        
        return $pdf->stream();
    }

    public function printWhiPds($id)
    {
        $pds = ProductDataSheet::with('productPhysicoChemicalAnalyses', 'productMicrobiologicalAnalysis', 'productHeavyMetal', 'productNutritionalInformation', 'productAllergens', 'productPotentialBenefit')->findOrFail($id);

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('products.print_whi_pds',
            array(
                'pds' => $pds
            )
        )
        ->setPaper('a4', 'portrait');
        
        return $pdf->stream();
    }

    public function printPbiPds($id)
    {
        $pds = ProductDataSheet::with('productPhysicoChemicalAnalyses', 'productMicrobiologicalAnalysis', 'productHeavyMetal', 'productNutritionalInformation', 'productAllergens', 'productPotentialBenefit')->findOrFail($id);

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('products.print_pbi_pds',
            array(
                'pds' => $pds
            )
        )
        ->setPaper('a4', 'portrait');
        
        return $pdf->stream();
    }
}
