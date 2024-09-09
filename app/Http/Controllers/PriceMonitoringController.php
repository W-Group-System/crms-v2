<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Client;
use App\FileActivity;
use App\PaymentTerms;
use App\PrfFile;
use App\PriceMonitoring;
use App\PriceRequestProduct;
use App\Product;
use App\SalesUser;
use App\SrfFile;
use App\TransactionLogs;
use App\Contact;
use App\Helpers\Helpers;
use App\PrfProgress;
use App\PriceRequestGae;
use App\ProductApplication;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Models\Audit;
use App\ProductMaterialsComposition;
use App\SalesApprovers;
use RealRashid\SweetAlert\Facades\Alert;

class PriceMonitoringController extends Controller
{
    // List 
    public function index(Request $request)
    {   
        $search = $request->input('search');
        $open = $request->open;
        $close = $request->close;
        $price_monitorings = PriceMonitoring::with(['client', 'product_application', 'requestPriceProducts'])
        ->when($request->has('open') && $request->has('close'), function ($query) use ($request) {
            $query->whereIn('Status', [$request->open, $request->close]);
        })
        ->when($request->has('open') && !$request->has('close'), function ($query) use ($request) {
            $query->where('Status', $request->open);
        })
        ->when($request->has('close') && !$request->has('open'), function ($query) use ($request) {
            $query->where('Status', $request->close);
        })
        ->where(function ($query) use ($search) {
            $query->where('PrfNumber', 'LIKE', '%' . $search . '%')
                ->orWhere('DateRequested', 'LIKE', '%' . $search . '%')
                ->orWhereHas('client', function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                });
                // ->orWhereHas('product_application', function ($q) use ($search) {
                //     $q->where('name', 'LIKE', '%' . $search . '%');
                // })
                // ->orWhere('RpeResult', 'LIKE', '%' . $search . '%');
        })
        ->when(auth()->user()->role->type == 'IS', function ($query) {
            $query->where('PrfNumber', 'LIKE', 'PRF-IS%');
        })
        ->when(auth()->user()->role->type == 'LS', function ($query) {
            $query->where('PrfNumber', 'LIKE', 'PRF-LS%');
        })
        ->orderBy('id', 'desc')
        ->paginate(10);
        $productApplications = ProductApplication::all(); 
        $clients = Client::where(function($query) {
            if (auth()->user()->role->name == "Department Admin")
            {
                $query->where('PrimaryAccountManagerId', auth()->user()->id)
                    ->orWhere('PrimaryAccountManagerId', auth()->user()->user_id)
                    ->orWhere('SecondaryAccountManagerId', auth()->user()->id)
                    ->orWhere('SecondaryAccountManagerId', auth()->user()->user_id);
            }
            if (auth()->user()->role->name == "Staff L2")
            {
                $query->where('PrimaryAccountManagerId', auth()->user()->id)
                    ->orWhere('PrimaryAccountManagerId', auth()->user()->user_id)
                    ->orWhere('SecondaryAccountManagerId', auth()->user()->id)
                    ->orWhere('SecondaryAccountManagerId', auth()->user()->user_id);
            }
            if (auth()->user()->role->name == "Staff L1")
            {
                $query->where('PrimaryAccountManagerId', auth()->user()->id)
                    ->orWhere('PrimaryAccountManagerId', auth()->user()->user_id)
                    ->orWhere('SecondaryAccountManagerId', auth()->user()->id)
                    ->orWhere('SecondaryAccountManagerId', auth()->user()->user_id);
            }
        })
        ->get();
        $loggedInUser = Auth::user(); 
        $role = $loggedInUser->role;
        $withRelation = $role->type == 'LS' ? 'localSalesApprovers' : 'internationalSalesApprovers';
        if ($role->name == 'Staff L2') {
            $salesApprovers = SalesApprovers::where('SalesApproverId', $loggedInUser->id)->pluck('UserId');
            $primarySalesPersons = User::whereIn('id', $salesApprovers)->orWhere('id', $loggedInUser->id)->get();
            // $secondarySalesPersons = User::whereIn('id', $salesApprovers)->orWhere('id', $loggedInUser->id)->get();
            $secondarySalesPersons = User::whereIn('id',$loggedInUser->salesApproverById->pluck('SalesApproverId'))->orWhere('id', $loggedInUser->id)->get();
            
        } else {
            $primarySalesPersons = User::with($withRelation)->where('id', $loggedInUser->id)->get();
            $secondarySalesPersons = User::whereIn('id', $loggedInUser->salesApproverById->pluck('SalesApproverId'))->get();
        }
        $products = Product::where('status', '4')->get();
        $payment_terms = PaymentTerms::all();
        $pricegaes = PriceRequestGae::get();
        $payment_terms = PaymentTerms::all();
        return view('price_monitoring_ls.index', compact('price_monitorings','clients','primarySalesPersons', 'secondarySalesPersons', 'search', 'products', 'payment_terms', 'open', 'close', 'productApplications', 'pricegaes' )); 
    }

    public function store(Request $request)
    {
        $user = Auth::user(); 
        // $salesUser = SalesUser::where('SalesUserId', $user->user_id)->first();
        // $type = $salesUser->Type == 2 ? 'IS' : 'LS';
        // $year = Carbon::parse($request->input('DateRequested'))->format('y');
        // $lastEntry = PriceMonitoring::where('PrfNumber', 'LIKE', "Prf-{$type}-%")
        //             ->orderBy('id', 'desc')
        //             ->first();
        // $lastNumber = $lastEntry ? intval(substr($lastEntry->PrfNumber, -4)) : 0;
        // $newIncrement = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        // $prfNo = "PRF-{$type}-{$year}-{$newIncrement}";
        $prfNo = null;
        if (auth()->user()->department_id == 38)
        {
            $checkPrf = PriceMonitoring::select('PrfNumber')->where('PrfNumber', 'LIKE', "%PRF-LS%")->orderBy('PrfNumber', 'desc')->first();
            $count = substr($checkPrf->PrfNumber, 10);
            $totalCount = $count + 1;
            $deptCode = 'LS';
            
            $prfNo = 'PRF'.'-'.$deptCode.'-'.date('y').'-'.$totalCount;
        }

        if (auth()->user()->department_id == 5)
        {
            $checkPrf = PriceMonitoring::select('PrfNumber')->where('PrfNumber', 'LIKE', "%PRF-IS%")->orderBy('PrfNumber', 'desc')->first();
            $count = substr($checkPrf->PrfNumber, 10);
            $totalCount = $count + 1;
            $deptCode = 'IS';
            
            $prfNo = 'PRF'.'-'.$deptCode.'-'.date('y').'-'.$totalCount;
        }

        $priceMonitoringData = PriceMonitoring::create([
            'PrfNumber' => $prfNo,
            'PrimarySalesPersonId' => $request->input('PrimarySalesPersonId'),
            'SecondarySalesPersonId' => $request->input('SecondarySalesPersonId'),
            'DateRequested' => $request->input('DateRequested'),
            'ClientId' => $request->input('ClientId'),
            'PriceRequestPurpose' => $request->input('PriceRequestPurpose'),
            'ShipmentTerm' => $request->input('ShipmentTerm'),
            'PaymentTermId' => $request->input('PaymentTerm'),
            'OtherCostRequirements' => $request->input('OtherCostRequirement'),
            'IsWithCommission' =>  $request->input('WithCommission') ? 1 : 0, 
            'Commission' => $request->input('EnterCommission'),
            'Remarks' => $request->input('Remarks'),
            'Status' => '10',
            'Progress' => '10',

        ]);
            PriceRequestProduct::create([
                'PriceRequestFormId' => $priceMonitoringData->id,
                'Type' => $request->input('Type'),
                'QuantityRequired' => $request->input('QuantityRequired'),
                'ProductId' => $request->input('Product'),
                'ProductRmc' => $request->input('Rmc'),
                'IsalesCommission'  => $request->input('Commision'),
                'IsalesShipmentCost' => $request->input('ShipmentCost'),
                'IsalesFinancingCost' => $request->input('FinancingCost'),
                'IsalesOthers' => $request->input('Others'),
                'IsalesTotalBaseCost' => $request->input('TotalBaseCost'),
                'IsalesBaseSellingPrice' => $request->input('BaseSellingPrice'),
                'IsalesOfferedPrice' => $request->input('OfferedPrice'),
                'IsalesMargin' => $request->input('Margin'),
                'IsalesMarginPercentage' => $request->input('MarginPercent'),
        ]);
                    return redirect()->back()->with('success', 'Price updated successfully.');
    }

    public function update(Request $request, $id)
    {
        $prf = PriceMonitoring::with(['client', 'product_application', 'requestPriceProducts'])->findOrFail($id);
    
        $prf->PrimarySalesPersonId = $request->input('PrimarySalesPersonId');
        $prf->SecondarySalesPersonId = $request->input('SecondarySalesPersonId');
        $prf->DateRequested = $request->input('DateRequested');
        $prf->ClientId = $request->input('ClientId');
        $prf->PriceRequestPurpose = $request->input('PriceRequestPurpose');
        $prf->ShipmentTerm = $request->input('ShipmentTerm');
        $prf->PaymentTermId = $request->input('PaymentTermId');
        $prf->OtherCostRequirements = $request->input('OtherCostRequirement');
        $prf->IsWithCommission = $request->input('WithCommission') ? 1 : 0;
        $prf->Commission = $request->input('EnterCommission');
        $prf->Remarks = $request->input('Remarks');
        $prf->IsAccepted = $request->input('IsAccepted') ? 1 : 0;
        $prf->PriceBid = $request->input('PriceBid');
        $prf->DispositionRemarks = $request->input('DispositionRemarks');
        $prf->save();
    
       
        $requestPriceProduct = $prf->requestPriceProducts()->find($request->input('requestPriceId'));
    
        if ($requestPriceProduct) {
            $requestPriceProduct->update([
                'Type' => $request->input('Type'),
                'QuantityRequired' => $request->input('QuantityRequired'),
                'ProductId' => $request->input('Product'),
                'ProductRmc' => $request->input('Rmc'),
                'IsalesCommission' => $request->input('Commission'),
                'IsalesShipmentCost' => $request->input('ShipmentCost'),
                'IsalesFinancingCost' => $request->input('FinancingCost'),
                'IsalesOthers' => $request->input('Others'),
                'IsalesTotalBaseCost' => $request->input('TotalBaseCost'),
                'IsalesBaseSellingPrice' => $request->input('BaseSellingPrice'),
                'IsalesOfferedPrice' => $request->input('OfferedPrice'),
                'IsalesMargin' => $request->input('Margin'),
                'IsalesMarginPercentage' => $request->input('MarginPercent'),
            ]);
        } else {
            $prf->requestPriceProducts()->create([
                'PriceRequestFormId' => $id, 
                'Type' => $request->input('Type'),
                'QuantityRequired' => $request->input('QuantityRequired'),
                'ProductId' => $request->input('Product'),
                'ProductRmc' => $request->input('Rmc'),
                'IsalesCommission' => $request->input('Commission'),
                'IsalesShipmentCost' => $request->input('ShipmentCost'),
                'IsalesFinancingCost' => $request->input('FinancingCost'),
                'IsalesOthers' => $request->input('Others'),
                'IsalesTotalBaseCost' => $request->input('TotalBaseCost'),
                'IsalesBaseSellingPrice' => $request->input('BaseSellingPrice'),
                'IsalesOfferedPrice' => $request->input('OfferedPrice'),
                'IsalesMargin' => $request->input('Margin'),
                'IsalesMarginPercentage' => $request->input('MarginPercent'),
            ]);
        }
        
    
        return redirect()->back()->with('success', 'Price Request updated successfully');
    }

    public function view($id)
    {
        $price_monitorings = PriceMonitoring::with(['client', 'product_application', 'requestPriceProducts'])->findOrFail($id);
        $prfNumber = $price_monitorings->id;
        $prfFileUploads = PrfFile::where('PriceRequestFormId', $prfNumber)->get();
        $clientId = $price_monitorings->ClientId;
        $activities = Activity::where('ClientId', $clientId)->get();
        $transactionLogs = TransactionLogs::where('Type', '50')
        ->where('TransactionId', $prfNumber)
        ->get();

        $audits = Audit::where('auditable_id', $prfNumber)
        ->whereIn('auditable_type', [PriceMonitoring::class, PrfFile::class])
        ->get();

        $mappedAudits = $audits->map(function ($audit) {
            $details = '';
            if ($audit->auditable_type === 'App\PrfFile') {
                $details = $audit->event . " " . 'PRF Files';
            } elseif ($audit->auditable_type === 'App\PriceMonitoring') 
            {$details = $audit->event . " " . 'Price Monitoring Request';}
            // {
            //     if (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 20) {
            //         $details = "Approve sample request entry";
            //     } elseif (isset($audit->new_values['Progress']) && ($audit->new_values['Progress'] == 30 || $audit->new_values['Progress'] == 80)) {
            //         $details = "Approve sample request entry";
            //     } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 35) {
            //         $details = "Receive sample request entry";
            //     } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 55) {
            //         $details = "Pause sample request transaction." . isset($audit->new_values['Remarks']);
            //     } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 50) {
            //         $details = "Start sample request transaction";
            //     } else {
            //         $details = $audit->event . " " . 'Sample Request';
            //     }
            // }
            return (object) [
                'CreatedDate' => $audit->created_at,
                'full_name' => $audit->user->full_name,
                'Details' => $details,
            ];
        });
    
        $mappedLogs = $transactionLogs->map(function ($log) {
            return (object) [
                'CreatedDate' => $log->ActionDate,
                'full_name' => $log->historyUser->full_name,
                'Details' => $log->Details,
            ];
        });
    
        $mappedLogsCollection = collect($mappedLogs);
        $mappedAuditsCollection = collect($mappedAudits);
    
        $combinedLogs = $mappedLogsCollection->merge($mappedAuditsCollection);
        return view('price_monitoring.view', compact('price_monitorings','prfFileUploads', 'activities', 'combinedLogs'));
    }
    
    public function getClientDetails($id)
    {
        $client = Client::with(['clientregion', 'clientcountry'])->find($id);
        if ($client) {
            return response()->json([
                'ClientRegionId' => $client->ClientRegionId,
                'RegionName' => $client->clientregion->Name,
                'ClientCountryId' => $client->ClientCountryId,
                'CountryName' => $client->clientcountry->Name
            ]);
        }
    }

    public function delete($id)
    {
        $data = PriceMonitoring::with(['client', 'product_application', 'requestPriceProducts'])->findOrFail($id);
        foreach ($data->requestPriceProducts as $requestPriceProduct) {
            $requestPriceProduct->delete();
        }
        $data->delete();
    }

    public function uploadFile(Request $request)
    {
        $files = $request->file('prf_file');
        $names = $request->input('name');
        $prfId = $request->input('prf_id');
        
        if ($files) {
            foreach ($files as $index => $file) {
            $name = $names[$index];
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/prfFiles', $fileName);
            $fileUrl = '/storage/prfFiles/' . $fileName;       
            $uploadedFile = new PrfFile();
            $uploadedFile->PriceRequestFormId = $prfId;
            $uploadedFile->Name = $name;
            $uploadedFile->Path = $fileUrl;
            $uploadedFile->save();
            }
        }
        
        return redirect()->back()->with('success', 'File(s) Stored successfully');
    }

    public function editFile(Request $request, $id)
    {
        $prfFile = PrfFile::findOrFail($id);
        if ($request->has('name')) {
            $prfFile->Name = $request->input('name');
        }
        if ($request->hasFile('prf_file')) {
            $file = $request->file('prf_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/prfFiles', $fileName);
            $fileUrl = '/storage/prfFiles/' . $fileName;

            $prfFile->Path = $fileUrl;
        }

        $prfFile->save();

        return redirect()->back()->with('success', 'File updated successfully');
    }

    public function deleteFile($id)
    {
        try { 
            $prfFile = PrfFile::findOrFail($id); 
            $prfFile->delete();  
            return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete File.'], 500);
        }
    }

    public function deleteActivity($id)
    {
        try { 
            $activity = Activity::findOrFail($id); 
            $activity->delete();  
            return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete File.'], 500);
        }
    }
    
    // public function indexLocal(Request $request)
    // {   
    //     $search = $request->input('search');
    //     $open = $request->open;
    //     $close = $request->close;
    //     $price_monitorings = PriceMonitoring::with(['client', 'product_application', 'requestPriceProducts', 'productMaterialComposition'])
    //     ->when($request->has('open') && $request->has('close'), function($query)use($request) {
    //         $query->whereIn('Status', [$request->open, $request->close]);
    //     })
    //     ->when($request->has('open') && !$request->has('close'), function($query)use($request) {
    //         $query->where('Status', $request->open);
    //     })
    //     ->when($request->has('close') && !$request->has('open'), function($query)use($request) {
    //         $query->where('Status', $request->close);
    //     })
    //     ->where(function ($query) use ($search){
    //         $query->where('PrfNumber', 'LIKE', '%' . $search . '%')
    //         ->orWhere('DateRequested', 'LIKE', '%' . $search . '%')
    //         ->orWhereHas('client', function ($q) use ($search) {
    //             $q->where('name', 'LIKE', '%' . $search . '%');
    //         });
    //         // ->orWhereHas('product_application', function ($q) use ($search) {
    //         //     $q->where('name', 'LIKE', '%' . $search . '%');
    //         // })
    //         // ->orWhere('RpeResult', 'LIKE', '%' . $search . '%');
    //     })
        
    //     ->where('PrfNumber', 'LIKE', 'PRF-LS%')
    //     ->orderBy('id', 'desc')->paginate(10);
    //     $clients = Client::where('PrimaryAccountManagerId', auth()->user()->user_id)
    //     ->orWhere('SecondaryAccountManagerId', auth()->user()->user_id)
    //     ->get();
    //     $productApplications = ProductApplication::all(); 
    //     $users = User::wherehas('localsalespersons')->get();
    //     $products = Product::where('status', '4')->get();
    //     // $products = Product::get();
    //     $pricegaes = PriceRequestGae::get();
    //     $payment_terms = PaymentTerms::all();
    //     return view('price_monitoring_ls.index', compact('price_monitorings','clients','users', 'search', 'products', 'payment_terms', 'productApplications', 'pricegaes', 'open', 'close',)); 
    // }

    public function storeLocalSalePre(Request $request)
    {
        $user = Auth::user(); 
        $salesUser = SalesUser::where('SalesUserId', $user->user_id)->first();
        $type = $salesUser->Type == 2 ? 'IS' : 'LS';
        $year = Carbon::parse($request->input('DateRequested'))->format('y');
        $lastEntry = PriceMonitoring::where('PrfNumber', 'LIKE', "Prf-{$type}-%")
                    ->orderBy('id', 'desc')
                    ->first();
        $lastNumber = $lastEntry ? intval(substr($lastEntry->PrfNumber, -4)) : 0;
        $newIncrement = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $prfNo = "PRF-{$type}-{$year}-{$newIncrement}";

        $priceMonitoringData = PriceMonitoring::create([
            'PrfNumber' => $prfNo,
            'PrimarySalesPersonId' => $request->input('PrimarySalesPersonId'),
            'SecondarySalesPersonId' => $request->input('SecondarySalesPersonId'),
            'DateRequested' => $request->input('DateRequested'),
            'ClientId' => $request->input('ClientId'),
            'ContactId' => $request->input('ClientContactId'),
            'ValidityDate' => $request->input('ValidityDate'),
            'PackagingType' => $request->input('PackagingType'),
            'Moq' => $request->input('Moq'),
            'ShelfLife' => $request->input('ShelfLife'),
            // 'IsWithCommission' =>  $request->input('WithCommission') ? 1 : 0, 
            // 'Commission' => $request->input('EnterCommission'),
            'ShipmentTerm' => $request->input('ShipmentTerm'),
            'Destination' => $request->input('Destination'),
            'PaymentTermId' => $request->input('PaymentTerm'),
            'PriceRequestPurpose' => $request->input('PriceRequestPurpose'),
            'PriceLockPeriod' => $request->input('DeliverySchedule'),
            'TaxType' => $request->input('TaxType'),
            'OtherRemarks' => $request->input('OtherRemarks'),
            'Status' => '10',
            'Progress' => '10',

        ]);

        foreach ($request->input('Product') as $key => $value) {
            PriceRequestProduct::create([
                'PriceRequestFormId' => $priceMonitoringData->id,
                'ProductId' =>$request->input('Product')[$key],
                'Type' => $request->input('Type')[$key],
                'ApplicationId' => $request->input('ApplicationId')[$key],
                'QuantityRequired' => $request->input('QuantityRequired')[$key],
                'ProductRmc' => $request->input('Rmc')[$key],
                'LsalesDirectLabor' => $request->input('DirectLabor')[$key],
                'LsalesFactoryOverhead' => $request->input('FactoryOverhead')[$key],
                'LsalesTotalManufacturingCost' => $request->input('TotalManufacturingCost')[$key],
                'LsalesBlendingLoss' => $request->input('BlendingLoss')[$key],
                'LsalesDeliveryType' => $request->input('DeliveryType')[$key],
                'LsalesDeliveryCost' => $request->input('DeliveryCost')[$key],
                'OtherCostRequirements' => $request->input('OtherCostRequirement')[$key],
                'LsalesTotalOperatingCost' => $request->input('TotalManufacturingCost')[$key],
                'LsalesFinancingCost' => $request->input('FinancingCost')[$key],
                'PriceRequestGaeId' => $request->input('PriceGae')[$key],
                'LsalesGaeValue' => $request->input('GaeCost')[$key],
                'LsalesMarkupPercent' => $request->input('MarkupPercent')[$key],
                'LsalesMarkupValue' => $request->input('MarkupPhp')[$key],
                'LsalesTotalProductCost' => $request->input('TotalProductCost')[$key],
                'LsalesSellingPricePhp' => $request->input('SellingPricePhp')[$key],
                'LsalesSellingPriceVat' => $request->input('SellingPriceVat')[$key],


        ]);
    }
                    return redirect()->back()->with('success', 'Prices updated successfully.');
    }

    public function LocalSalesUpdate(Request $request, $id)
    {
        $priceMonitoringData = PriceMonitoring::with('requestPriceProducts')->findOrFail($id);
    
        $priceMonitoringData->PrimarySalesPersonId = $request->input('PrimarySalesPersonId');
        $priceMonitoringData->SecondarySalesPersonId = $request->input('SecondarySalesPersonId');
        $priceMonitoringData->DateRequested = $request->input('DateRequested');
        $priceMonitoringData->ClientId = $request->input('ClientId');
        $priceMonitoringData->ContactId = $request->input('ContactId');
        $priceMonitoringData->ValidityDate = $request->input('ValidityDate');
        $priceMonitoringData->PackagingType = $request->input('PackagingType');
        $priceMonitoringData->Moq = $request->input('Moq');
        $priceMonitoringData->ShelfLife = $request->input('ShelfLife');
        // $priceMonitoringData->IsWithCommission = $request->input('WithCommission') ? 1 : 0;
        // $priceMonitoringData->Commission = $request->input('EnterCommission');
        $priceMonitoringData->ShipmentTerm = $request->input('ShipmentTerm');
        $priceMonitoringData->Destination = $request->input('Destination');
        $priceMonitoringData->PaymentTermId = $request->input('PaymentTerm');
        $priceMonitoringData->PriceRequestPurpose = $request->input('PriceRequestPurpose');
        $priceMonitoringData->PriceLockPeriod = $request->input('DeliverySchedule'); 
        $priceMonitoringData->TaxType = $request->input('TaxType');
        $priceMonitoringData->OtherRemarks = $request->input('OtherRemarks');
        $priceMonitoringData->save();
    
        foreach ($request->input('Product') as $key => $value) {
            $productId = $request->input('product_id.' . $key); 
    
            $priceMonitoringData->requestPriceProducts()->updateOrCreate(
                ['id' => $productId],
                [
                    'PriceRequestFormId' => $id, 
                    'ProductId' => $value,
                    'Type' => $request->input('Type.' . $key),
                    'ApplicationId' => $request->input('ApplicationId.' . $key),
                    'QuantityRequired' => $request->input('QuantityRequired.' . $key),
                    'ProductRmc' => $request->input('Rmc.' . $key),
                    'LsalesDirectLabor' => $request->input('DirectLabor.' . $key),
                    'LsalesFactoryOverhead' => $request->input('FactoryOverhead.' . $key),
                    'LsalesBlendingLoss' => $request->input('BlendingLoss.' . $key),
                    'LsalesDeliveryType' => $request->input('DeliveryType.' . $key),
                    'LsalesDeliveryCost' => $request->input('DeliveryCost.' . $key),
                    'OtherCostRequirements' => $request->input('OtherCostRequirement.' . $key),
                    'LsalesFinancingCost' => $request->input('FinancingCost.' . $key),
                    'PriceRequestGaeId' => $request->input('PriceGae.' . $key),
                    'LsalesGaeValue' => $request->input('GaeCost.' . $key),
                    'LsalesMarkupPercent' => $request->input('MarkupPercent.' . $key),
                    'LsalesMarkupValue' => $request->input('MarkupPhp.' . $key),
                    'LsalesTotalManufacturingCost' => $request->input('TotalManufacturingCost.' . $key),
                    'LsalesTotalOperatingCost' => $request->input('TotalOperatingCost.' . $key),
                    'LsalesTotalProductCost' => $request->input('TotalProductCost.' . $key),
                    'LsalesSellingPricePhp' => $request->input('SellingPricePhp.' . $key),
                    'LsalesSellingPriceVat' => $request->input('SellingPriceVat.' . $key),

                ]
            );
        }
    
        return redirect()->back()->with('success', 'Price Request updated successfully');
    }

    public function localview($id)
    {
        $price_monitorings = PriceMonitoring::with(['client', 'product_application', 'requestPriceProducts'])->findOrFail($id);
        $prfNumber = $price_monitorings->id;
        $PriceRequestNumber = $price_monitorings->PrfNumber;
        $prfFileUploads = PrfFile::where('PriceRequestFormId', $prfNumber)->get();
        $products = Product::where('status', '4')->get();
        $productApplications = ProductApplication::all(); 
        $pricegaes = PriceRequestGae::get();
        $payment_terms = PaymentTerms::all();
        $clientId = $price_monitorings->ClientId;
        $progresses = PrfProgress::all();
        $clients = Client::where(function($query) {
            if (auth()->user()->role->name == "Department Admin")
            {
                $query->where('PrimaryAccountManagerId', auth()->user()->id)
                    ->orWhere('PrimaryAccountManagerId', auth()->user()->user_id);
            }
            if (auth()->user()->role->name == "Staff L1")
            {
                $query->where('PrimaryAccountManagerId', auth()->user()->id)
                    ->orWhere('PrimaryAccountManagerId', auth()->user()->user_id);
            }
        })
        ->where(function($query) {
            if (auth()->user()->role->name == "Staff L2")
            {
                $query->where('SecondaryAccountManagerId', auth()->user()->id)
                    ->orWhere('SecondaryAccountManagerId', auth()->user()->user_id);
            }
        })
        ->get();
        $users = User::wherehas('localsalespersons')->get();
        $activities = Activity::where('TransactionNumber', $PriceRequestNumber)->get();
        $transactionLogs = TransactionLogs::where('Type', '50')
        ->where('TransactionId', $prfNumber)
        ->get();

        $audits = Audit::where('auditable_id', $prfNumber)
        ->whereIn('auditable_type', [PriceMonitoring::class, PrfFile::class])
        ->get();

        $mappedAudits = $audits->map(function ($audit) {
            $details = '';
            if ($audit->auditable_type === 'App\PrfFile') {
                $details = $audit->event . " " . 'PRF Files';
            } elseif ($audit->auditable_type === 'App\PriceMonitoring') 
            {$details = $audit->event . " " . 'Price Monitoring Request';}
            // {
            //     if (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 20) {
            //         $details = "Approve sample request entry";
            //     } elseif (isset($audit->new_values['Progress']) && ($audit->new_values['Progress'] == 30 || $audit->new_values['Progress'] == 80)) {
            //         $details = "Approve sample request entry";
            //     } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 35) {
            //         $details = "Receive sample request entry";
            //     } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 55) {
            //         $details = "Pause sample request transaction." . isset($audit->new_values['Remarks']);
            //     } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 50) {
            //         $details = "Start sample request transaction";
            //     } else {
            //         $details = $audit->event . " " . 'Sample Request';
            //     }
            // }
            return (object) [
                'CreatedDate' => $audit->created_at,
                'full_name' => $audit->user->full_name,
                'Details' => $details,
            ];
        });
    
        $mappedLogs = $transactionLogs->map(function ($log) {
            return (object) [
                'CreatedDate' => $log->ActionDate,
                'full_name' => $log->historyUser->full_name,
                'Details' => $log->Details,
            ];
        });
    
        $mappedLogsCollection = collect($mappedLogs);
        $mappedAuditsCollection = collect($mappedAudits);
    
        $combinedLogs = $mappedLogsCollection->merge($mappedAuditsCollection);
        return view('price_monitoring_ls.view', compact('price_monitorings','prfFileUploads', 'activities', 'combinedLogs', 'clients','users','products', 'productApplications','pricegaes', 'payment_terms'));
    }

    public function getPrfContacts($clientId)
    {
        $contacts = Contact::where('CompanyId', $clientId)->pluck('ContactName', 'id');
        return response()->json($contacts);
    }

    public function getGaeDetails($id)
    {
        $priceGae = PriceRequestGae::all()->find($id);
        if ($priceGae) {
            return response()->json([
                'Cost' => $priceGae->Cost
            ]);
        }
    }

    public function getProductRmc($id)
    {
        $productRawMaterials = ProductMaterialsComposition::where('ProductId', $id)->get();
        $rmcValue = rmc($productRawMaterials, $id);
        $phpValue = usdToPhp($rmcValue);
        return response()->json(['rmc' => $phpValue]);
    }

    public function getClientDetailsL($id)
    {
        $client = Client::with('clientPaymentTerm')->find($id);
        if ($client) {
            return response()->json([
                'PaymentTerm' => $client->clientPaymentTerm->Name,
            ]);
        }
    }

    public function deleteProduct(Request $request , $id)
    {
        $product = PriceRequestProduct::find($id); 
        if ($product) {
            $product->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

public function ClosePrf( Request $request , $id)
    {
        $closePrf = PriceMonitoring::find($id);    
        if ($closePrf) {
            $closePrf->IsAccepted = $request->input('IsAccepted') ? 1 : 0;
            $closePrf->BuyerRefCode = $request->input('BuyersRefCode'); 
            $closePrf->DispositionRemarks = $request->input('DispositionRemarks'); 
            $closePrf->Progress = '30'; 
            $closePrf->Status = '30'; 


        }
            $closePrf->save();
            Alert::success('Request Closed')->persistent('Dismiss');
            return back();
    }
    public function ApprovePrf(Request $request, $id)
    {
        $approvePrf = PriceMonitoring::find( $id);    
        if ($approvePrf) {
            $approvePrf->ApprovalRemarks = $request->input('Remarks');
            $approvePrf->Progress = '20'; 


        }
            $approvePrf->save();

            Alert::success('Successfully Saved')->persistent('Dismiss');
            return back();
        }    

        public function ApproveManagerPrf(Request $request, $id)
    {
        $approvePrf = PriceMonitoring::find( $id);    
        if ($approvePrf) {
            $approvePrf->ApprovalRemarks = $request->input('Remarks');
            $approvePrf->Progress = '40'; 


        }
            $approvePrf->save();

            Alert::success('Successfully Saved')->persistent('Dismiss');
            return back();
        }   
        

        public function ReopenPrf(Request $request, $id)
        {
            $reopenPrf = PriceMonitoring::findOrFail($id);
            $reopenPrf->Status = 10;
            $reopenPrf->Progress = 25;
            $reopenPrf->save();
    
            Alert::success('The status are now open')->persistent('Dismiss');
            return back();
        }

    public function PrfActivityStore(Request $request) 
    {
        $request->validate([
            'path.*' => 'mimes:jpg,pdf,docx'
        ]);

        $activityNumber = null;
            $checkActivity = Activity::select('ActivityNumber')->where('ActivityNumber', 'LIKE', "%ACT-LS%")->orderBy('ActivityNumber', 'desc')->first();
            $count = substr($checkActivity->ActivityNumber, 10);
            $totalCount = $count + 1;
            $deptCode = 'LS';
            
            $activityNumber = 'ACT'.'-'.$deptCode.'-'.date('y').'-'.$totalCount;
        
        
        $activity = new Activity; 
        $activity->Type = $request->Type;
        $activity->ActivityNumber = $activityNumber;
        $activity->RelatedTo = $request->RelatedTo;
        $activity->ClientId = $request->ClientId;
        $activity->TransactionNumber = $request->TransactionNumber;
        $activity->ClientContactId = $request->ClientContactId;
        $activity->ScheduleFrom = $request->ScheduleFrom;
        $activity->PrimaryResponsibleUserId = $request->PrimarySalesPersonId;
        $activity->ScheduleTo = $request->ScheduleTo;
        $activity->SecondaryResponsibleUserId = $request->SecondarySalesPersonId;
        $activity->Title = $request->Title;
        $activity->Description = $request->Description;
        $activity->Status = 10;
        $activity->save();
        
        if ($request->has('path'))
        {
            $attachments = $request->file('path');
            foreach($attachments as $attachment)
            {
                $name = time().'_'.$attachment->getClientOriginalName();
                $attachment->move(public_path().'/activity_attachment/', $name);
    
                $file_name = '/activity_attachment/'.$name;
                
                $activityFiles = new FileActivity;
                $activityFiles->activity_id = $activity->id;
                $activityFiles->path = $file_name;
                $activityFiles->save();
            }
        }
        
        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function PrfActivityUpdate(Request $request, $id) 
    {
        $priceMonitoringData = Activity::findOrFail($id);
        $request->validate([
            'path.*' => 'mimes:jpg,pdf,docx'
        ]);

        $priceMonitoringData->Type = $request->Type;
        $priceMonitoringData->RelatedTo = $request->RelatedTo;
        $priceMonitoringData->ClientId = $request->ClientId;
        $priceMonitoringData->TransactionNumber = $request->TransactionNumber;
        $priceMonitoringData->ClientContactId = $request->ClientContactId;
        $priceMonitoringData->ScheduleFrom = $request->ScheduleFrom;
        $priceMonitoringData->PrimaryResponsibleUserId = $request->PrimarySalesPersonId;
        $priceMonitoringData->ScheduleTo = $request->ScheduleTo;
        $priceMonitoringData->SecondaryResponsibleUserId = $request->SecondarySalesPersonId;
        $priceMonitoringData->Title = $request->Title;
        $priceMonitoringData->Description = $request->Description;
        $priceMonitoringData->DateClosed = $request->DateClosed;
        $priceMonitoringData->Response = $request->Response;
        $priceMonitoringData->Description = $request->Description;
        $priceMonitoringData->Status = $request->Status;
        $priceMonitoringData->save();
        
        $attachments = $request->file('path');
        foreach($attachments as $attachment)
        {
            $name = time().'_'.$attachment->getClientOriginalName();
            $attachment->move(public_path().'/activity_attachment/', $name);

            $file_name = '/activity_attachment/'.$name;
            
            $activityFiles = new FileActivity;
            $activityFiles->activity_id = $priceMonitoringData->id;
            $activityFiles->path = $file_name;
            $activityFiles->save();
        }
        
        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

}
