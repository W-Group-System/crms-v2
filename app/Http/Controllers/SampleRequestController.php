<?php

namespace App\Http\Controllers;

use App\Activity;
use App\SampleRequest;
use App\Client;
use App\ConcernDepartment;
use App\Contact;
use App\IssueCategory;
use App\Product;
use App\ProductApplication;
use App\RndUser;
use App\SampleRequestProduct;
use App\SrfDetail;
use App\SrfFile;
use App\SrfPersonnel;
use App\SrfProgress;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SampleRequestController extends Controller
{
        public function index()
    {   
        $clients = Client::all();
        $contacts = Contact::all();
        $categories = IssueCategory::all();
        $departments = ConcernDepartment::all(); 
        $productApplications = ProductApplication::all();   
        $salesPersons = User::whereHas('salespersons')->get();
        $productCodes = Product::where('status', '4')->get();
       
        // $sampleRequestProducts = SampleRequestProduct::with('sampleRequest')
        // ->whereHas('sampleRequest', function ($query) {
        //     $query->where('status', 10);
        // })
        // // ->get()
        // ->paginate(25);

        $sampleRequests = SampleRequest::with('requestProducts') 
        ->where('status', 10) 
        ->paginate(25);

       
        return view('sample_requests.index', compact('sampleRequests','clients', 'contacts', 'categories', 'departments', 'salesPersons', 'productApplications', 'productCodes'));
    }

    public function getSampleContactsByClientF($clientId)
    {
        $contacts = Contact::where('CompanyId', $clientId)->pluck('ContactName', 'id');
        return response()->json($contacts);
    }

    public function getSampleLastIncrementF($year, $clientCode)
    {
        $lastUniqueID = SampleRequest::where('SrfNumber', 'like', 'SRF-' . $clientCode . '-' . $year . '-%')
                            ->orderBy('SrfNumber', 'desc')
                            ->first();

        if ($lastUniqueID) {
            $parts = explode('-', $lastUniqueID->SrfNumber);
            $lastIncrement = end($parts);
        } else {
            $lastIncrement = '0000';
        }

        return response()->json(['lastIncrement' => $lastIncrement]);
    }   

    public function view($id)
    {
        $sampleRequest = SampleRequest::with('requestProducts')->findOrFail($id);
        $scrfNumber = $sampleRequest->Id;
        $clientId = $sampleRequest->ClientId;


        $activities = Activity::where('ClientId', $clientId)->get();
        $SrfSupplementary = SrfDetail::where('SampleRequestId', $scrfNumber)->get();
        $assignedPersonnel = SrfPersonnel::where('SampleRequestId', $scrfNumber)->get();
        $salesPersons = User::whereHas('rndUsers')->get();
        $srfFileUploads = SrfFile::where('SampleRequestId', $scrfNumber)->get();
        return view('sample_requests.view', compact('sampleRequest', 'SrfSupplementary', 'salesPersons', 'assignedPersonnel', 'activities', 'srfFileUploads'));
    }               

    public function update(Request $request, $id)
    {
        $srf = SampleRequest::with('requestProducts')->findOrFail($id);
    
        // $srf->DateRequested = $request->input('DateRequested');
        // $srf->DateRequested = Carbon::createFromFormat('m/d/Y', $request->input('DateRequested'))->format('Y-m-d');
        $srf->DateRequired = $request->input('DateRequired');
        $srf->DateStarted = $request->input('DateStarted');
        $srf->PrimarySalesPersonId = $request->input('PrimarySalesPerson');
        $srf->SecondarySalesPersonId = $request->input('SecondarySalesPerson');
        $srf->RefCode = $request->input('RefCode');
        $srf->SrfType = $request->input('SrfType');
        $srf->SoNumber = $request->input('SoNumber');
        $srf->ClientId = $request->input('ClientId');
        $srf->ContactId = $request->input('ClientContactId');
        $srf->InternalRemarks = $request->input('Remarks');
        $srf->Courier = $request->input('Courier');
        $srf->AwbNumber = $request->input('AwbNumber');
        $srf->DateDispatched = $request->input('DateDispatched');
        $srf->DateSampleReceived = $request->input('DateSampleReceived');
        $srf->DeliveryRemarks = $request->input('DeliveryRemarks');
        $srf->Note = $request->input('Note');
        // dd($request->input('DateRequested'));
        $srf->save();

        foreach ($request->input('ProductType') as $key => $value) {
            $product = $srf->requestProducts()->updateOrCreate(
                ['id' => $request->input('product_id')[$key]], 
                [
                    'ProductType' => $value,
                    'ApplicationId' => $request->input('ApplicationId')[$key],
                    'ProductCode' => $request->input('ProductCode')[$key],
                    'ProductDescription' => $request->input('ProductDescription')[$key],
                    'NumberOfPackages' => $request->input('NumberOfPackages')[$key],
                    'Quantity' => $request->input('Quantity')[$key],
                    'UnitOfMeasure' => $request->input('UnitOfMeasure')[$key],
                    'Label' => $request->input('Label')[$key],
                    'RpeNumber' => $request->input('RpeNumber')[$key],
                    'CrrNumber' => $request->input('CrrNumber')[$key],
                    'Remarks' => $request->input('RemarksProduct')[$key],
                ]
            );
        }

        return redirect()->back()->with('success', 'Sample Request updated successfully');
    }
    public function addSupplementary(Request $request)
        {
        SrfDetail::create([
                'SampleRequestId' => $request->input('srf_id'),
                'UserId' => auth()->user()->user_id,
                'DetailsOfRequest' => $request->input('details_of_request'),

            ]);
            return back();
        }

        public function editSupplementary(Request $request, $id)
        {
            $srfDetail = SrfDetail::findOrFail($id);
            $srfDetail->DetailsOfRequest = $request->input('details_of_request');
            $srfDetail->save();
        return back();
        }

        public function deleteSrfDetails($id)
    {
        try { 
            $srfDetail = SrfDetail::findOrFail($id); 
            $srfDetail->delete();  
            return response()->json(['success' => true, 'message' => 'Supplementary Detail deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete supplementary detail.'], 500);
        }
    }
    public function assignPersonnel(Request $request)
        {
        SrfPersonnel::create([
                'SampleRequestId' => $request->input('srf_id'),
                'CreatedDate' => now(), 
                'PersonnelType' => 20,
                'PersonnelUserId' => $request->input('PrimarySalesPerson'),
            
            ]);
            return back();
        }
        public function uploadFile(Request $request)
        {
            $files = $request->file('srf_file');
            $names = $request->input('name');
            $srfId = $request->input('srf_id');
        
            if ($files) {
                foreach ($files as $index => $file) {
                    $name = $names[$index];
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('public/srfFiles', $fileName);
                    $fileUrl = '/storage/srfFiles/' . $fileName;

        
                    $uploadedFile = new SrfFile();
                    $uploadedFile->SampleRequestId = $srfId;
                    $uploadedFile->Name = $name;
                    $uploadedFile->Path = $fileUrl;
                    $uploadedFile->save();
                }
            }
        
            return redirect()->back()->with('success', 'File(s) Stored successfully');
        }
        
    public function store(Request $request)
        {
            $refCode = $request->input('RefCode');
            $quantities = $request->input('Quantity');
        
            foreach ($quantities as $key => $quantity) {
                if ($refCode == 2 && $quantity < 1000) {
                    return redirect()->back()->with('error', 'Quantity must be at least 1000g for QCD.')->withInput();
                }
                if ($refCode == 1 && $quantity > 999) {
                    return redirect()->back()->with('error', 'Quantity must be 999g or less for RND.')->withInput();
                }
            }

            $samplerequest = SampleRequest::create([
                'SrfNumber' => $request->input('SrfNumber'),
                'DateRequested' => $request->input('DateRequested'),
                'DateRequired' => $request->input('DateRequired'),
                'DateStarted' => $request->input('DateStarted'),
                'PrimarySalesPersonId' => $request->input('PrimarySalesPerson'),
                'SecondarySalesPersonId' => $request->input('SecondarySalesPerson'),
                'SoNumber' => $request->input('SoNumber'),
                'RefCode' => $request->input('RefCode'),
                'Status' => '10',
                'Progress' => '10',
                'SrfType' => $request->input('SrfType'),
                'ClientId' => $request->input('ClientId'),
                'ContactId' => $request->input('ClientContactId'),
                'InternalRemarks' => $request->input('Remarks'),
                'Courier' => $request->input('Courier'),
                'AwbNumber' => $request->input('AwbNumber'),
                'DateDispatched' => $request->input('DateDispatched'),
                'DateSampleReceived' => $request->input('DateSampleReceived'),
                'DeliveryRemarks' => $request->input('DeliveryRemarks'),
                'Note' => $request->input('Note'),

            ]);
            $maxId = SampleRequestProduct::max('Id');
            foreach ($request->input('ProductType') as $key => $value) {
                SampleRequestProduct::create([
                    'Id' => $maxId + $key + 1, 
                    'SampleRequestId' => $samplerequest->Id,
                    'ProductType' => $request->input('ProductType')[$key],
                    'ApplicationId' => $request->input('ApplicationId')[$key],
                    'ProductCode' => $request->input('ProductCode')[$key],
                    'ProductDescription' => $request->input('ProductDescription')[$key],
                    'NumberOfPackages' => $request->input('NumberOfPackages')[$key],
                    'Quantity' => $request->input('Quantity')[$key],
                    'UnitOfMeasureId' => $request->input('UnitOfMeasure')[$key],
                    'ProductIndex' => $key + 1,
                    'Label' => $request->input('Label')[$key],
                    'RpeNumber' => $request->input('RpeNumber')[$key],
                    'CrrNumber' => $request->input('CrrNumber')[$key],
                    'Remarks' => $request->input('RemarksProduct')[$key],

                ]);
            }
            return redirect()->route('sample_request.index')->with('success', 'Sample Request created successfully.');
        }

        public function approveSrfSales($id)
        {
            $approveSrfSales = SampleRequest::find($id);
            
            if ($approveSrfSales) {
                $buttonClicked = request()->input('submitbutton');
                
                if ($buttonClicked === 'Approve to R&D') {
                    $approveSrfSales->Progress = 30; 
                    $approveSrfSales->InternalRemarks = request()->input('Remarks'); 
                } elseif ($buttonClicked === 'Approve to QCD') {
                    $approveSrfSales->Progress = 80;
                    $approveSrfSales->InternalRemarks = request()->input('submitbutton'); 
                }
        
                $approveSrfSales->save();
                return back();
            } 
        }    
        public function receiveSrf($id)
        {
            $receiveSrf = SampleRequest::find($id);
            
            if ($receiveSrf) {
            
                    $receiveSrf->Progress = 35; 
            }
                $receiveSrf->save();
                return back();
        } 
        public function startSrf($id)
        {
            $startSrf = SampleRequest::find($id);
            
            if ($startSrf) {
            
                    $startSrf->Progress = 50; 
                    $startSrf->DateStarted = now(); 
            }
                $startSrf->save();
                return back();
        }
        public function pauseSrf($id)
        {
            $pauseSrf = SampleRequest::find($id);
            
            if ($pauseSrf) {
                    $pauseSrf->Progress = 55; 
                    $pauseSrf->InternalRemarks = request()->input('Remarks'); 
            }
                $pauseSrf->save();
                return back();
        } 
    }    

