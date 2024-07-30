<?php

namespace App\Http\Controllers;

use App\BasePrice;
use App\Client;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    // Current List
    public function current(Request $request)
    {   
        $products = Product::with(['userById', 'userByUserId'])
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
                $q->where('ddw_number', "LIKE" ,"%".$request->search."%")
                    ->orWhere('code', "LIKE", "%".$request->search."%")
                    ->orWhereHas('userByUserId', function($q)use($request) {
                        $q->where('full_name', 'LIKE', "%".$request->search."%");
                    })
                    ->orWhereHas('userById', function($q)use($request) {
                        $q->where('full_name', 'LIKE', "%".$request->search."%");
                    });
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
                $q->where('ddw_number', "LIKE" ,"%".$request->search."%")
                    ->orWhere('code', "LIKE", "%".$request->search."%")
                    ->orWhereHas('userByUserId', function($q)use($request) {
                        $q->where('full_name', 'LIKE', "%".$request->search."%");
                    })
                    ->orWhereHas('userById', function($q)use($request) {
                        $q->where('full_name', 'LIKE', "%".$request->search."%");
                    });
            })
            // ->orWhere('code', $request->search)
            ->where('status', 1)
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
                $q->where('ddw_number', "LIKE" ,"%".$request->search."%")
                    ->orWhere('code', "LIKE", "%".$request->search."%")
                    ->orWhereHas('userByUserId', function($q)use($request) {
                        $q->where('full_name', 'LIKE', "%".$request->search."%");
                    })
                    ->orWhereHas('userById', function($q)use($request) {
                        $q->where('full_name', 'LIKE', "%".$request->search."%");
                    });
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
        $validator = Validator::make($request->all(), [
            'raw_materials' => 'array|min:1',
            'raw_materials.*' => 'distinct'
        ], [
            'raw_materials.*.distinct' => 'The raw materials should be unique.',
        ]);

        if ($validator->fails())
        {
            $msg = $validator->errors()->first();
            Alert::error($msg)->persistent('Dismiss');
        }
        else
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
        }

        return back();
    }

    public function addToNewProducts(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->status = 2;
        $product->save();

        if($request->action)
        {
            Alert::success('Successfully added to new products')->persistent("Dismiss");
            return redirect('/new_products');
        }
        else
        {
            return array('message' => 'Successfully added to new products');
        }

    }

    public function addToCurrentProducts(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->status = 4;
        $product->save();

        if ($request->action == "Current")
        {
            Alert::success('Successfully added to current products')->persistent("Dismiss");
            return redirect('/current_products');
        }
        else
        {
            return array('message' => 'Successfully added to current products');
        }
    }

    public function addToDraftProducts(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->status = 1;
        $product->save();

        if ($request->action == 'Draft')
        {
            Alert::success('Successfully added to draft products')->persistent("Dismiss");
            return redirect('/draft_products');
        }
        else
        {
            return array('message' => 'Successfully added to draft products');
        }
    }

    public function addToArchiveProducts(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->status = 5;
        $product->save();

        if ($request->action == "Archive")
        {
            Alert::success('Successfully added to archive')->persistent("Dismiss");
            return redirect('/archived_products');
        }
        else
        {
            return array('message' => 'Successfully added to archive products');
        }
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
            return back();
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
        return back();
    }

    public function viewPdsDetails($id)
    {
        $productDataSheet = ProductDataSheet::with(['products', 'productPotentialBenefit', 'productPhysicoChemicalAnalyses', 'productMicrobiologicalAnalysis', 'productHeavyMetal', 'productNutritionalInformation', 'productAllergens'])->where('id', $id)->first();

        return view('products.pds_details', array('pds' => $productDataSheet));
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
        $productFiles = ProductFiles::where('ProductId', $request->product_id)->delete();

        if($request->description)
        {
            foreach($request->description as $key=>$description)
            {
                $productFiles = new ProductFiles;
                $productFiles->ProductId = $request->product_id;
                $productFiles->Name = $request->name[$key];
                $productFiles->ClientId = $request->client[$key];
                $productFiles->Description = $description;
                
                if ($request->hasFile('files') && isset($request->file('files')[$key]))
                {
                    $files = $request->file('files');
                    foreach($files as $file)
                    {
                        $fileName = time().'_'.$file->getClientOriginalName();
                        $file->move(public_path().'/attachments/', $fileName);
    
                        $productFiles->Path = "/attachments/" . $fileName;
                    }
                }
                else 
                {
                    $productFiles->Path = $request->product_files[$key] ?? null;
                }

                $productFiles->save();
            }
        }

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
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
        $approveUsers = $users->firstWhere('user_id', $data->approved_by) ?? $users->firstWhere('id', $data->approved_by);

        $rawMaterials = RawMaterial::where('status', 'Active')->get();
        $client = Client::get();

        return view('products.draft_view', compact('data', 'product_applications', 'product_subcategories', 'userAccounts', 'approveUsers', 'rawMaterials', 'client'));
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
        $approveUsers = $users->firstWhere('user_id', $data->approved_by) ?? $users->firstWhere('id', $data->approved_by);

        $rawMaterials = RawMaterial::where('status', 'Active')->get();
        $client = Client::get();

        return view('products.new_view', compact('data', 'product_applications', 'product_subcategories', 'userAccounts', 'approveUsers', 'rawMaterials', 'client'));
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
        $approveUsers = $users->firstWhere('user_id', $data->approved_by) ?? $users->firstWhere('id', $data->approved_by);

        $rawMaterials = RawMaterial::where('status', 'Active')->get();
        $client = Client::get();

        return view('products.archived_view', compact('data', 'product_applications', 'product_subcategories', 'userAccounts', 'approveUsers', 'rawMaterials', 'client'));
    }
}
