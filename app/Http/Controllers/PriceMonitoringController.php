<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Client;
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
use App\PriceRequestGae;
use App\ProductApplication;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Models\Audit;
use App\ProductMaterialsComposition;

class PriceMonitoringController extends Controller
{
    // List 
    public function index(Request $request)
    {   
        $search = $request->input('search');
        $price_monitorings = PriceMonitoring::with(['client', 'product_application', 'requestPriceProducts'])
        ->where(function ($query) use ($search){
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
        ->where('PrfNumber', 'LIKE', 'PRF-IS%')
        ->orderBy('id', 'desc')->paginate(25);
        $clients = Client::all();
        $users = User::all();
        $products = Product::where('status', '4')->get();
        $payment_terms = PaymentTerms::all();
        return view('price_monitoring.index', compact('price_monitorings','clients','users', 'search', 'products', 'payment_terms')); 
    }

    public function store(Request $request)
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
                    return redirect()->back()->with('success', 'Base prices updated successfully.');
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
        $prf->IsWithCommission = $request->input('WithCommission');
        $prf->Commission = $request->input('EnterCommission');
        $prf->Remarks = $request->input('Remarks');
        $prf->save();
    
       
            $prf->requestPriceProducts()->where('Id',  $request->input('requestPriceId') )->updateOrCreate(
                [
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
                ]
            );
        
    
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
    
    public function indexLocal(Request $request)
    {   
        $search = $request->input('search');
        $price_monitorings = PriceMonitoring::with(['client', 'product_application', 'requestPriceProducts', 'productMaterialComposition'])
        ->where(function ($query) use ($search){
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
        ->where('PrfNumber', 'LIKE', 'PRF-LS%')
        ->orderBy('id', 'desc')->paginate(25);
        $clients = Client::all();
        $productApplications = ProductApplication::all(); 
        $users = User::all();
        $products = Product::where('status', '4')->get();
        // $products = Product::get();
        $pricegaes = PriceRequestGae::get();
        $payment_terms = PaymentTerms::all();
        return view('price_monitoring_ls.index', compact('price_monitorings','clients','users', 'search', 'products', 'payment_terms', 'productApplications', 'pricegaes')); 
    }

    public function storeLocalSaleRpe(Request $request)
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
            'Moq' => $request->input('Moq'),
            'ShelfLife' => $request->input('ShelfLife'),
            'IsWithCommission' =>  $request->input('WithCommission') ? 1 : 0, 
            'Commission' => $request->input('EnterCommission'),
            'ShipmentTerm' => $request->input('ShipmentTerm'),
            'Destination' => $request->input('Destination'),
            'PaymentTermId' => $request->input('PaymentTerm'),
            'OtherCostRequirements' => $request->input('OtherCostRequirement'),
            'PriceRequestPurpose' => $request->input('PriceRequestPurpose'),
            'PriceLockPeriod' => $request->input('DeliverySchedule'),
            'TaxType' => $request->input('TaxType'),
            'Status' => '10',
            'Progress' => '10',

        ]);
            PriceRequestProduct::create([
                'PriceRequestFormId' => $priceMonitoringData->id,
                'ProductId' => $request->input('Product'),
                'Type' => $request->input('Type'),
                'ApplicationId' => $request->input('ApplicationId'),
                'QuantityRequired' => $request->input('QuantityRequired'),
                'ProductRmc' => $request->input('Rmc'),
                'LsalesDirectLabor' => $request->input('DirectLabor'),
                'LsalesFactoryOverhead' => $request->input('FactoryOverhead'),
                'LsalesBlendingLoss' => $request->input('BlendingLoss'),
                'LsalesDeliveryType' => $request->input('DeliveryType'),
                'LsalesDeliveryCost' => $request->input('DeliveryCost'),
                'LsalesFinancingCost' => $request->input('FinancingCost'),
                'PriceRequestGaeId' => $request->input('PriceGae'),
                'LsalesGaeValue' => $request->input('GaeCost'),
                'LsalesMarkupPercent' => $request->input('MarkupPercent'),
                'LsalesMarkupValue' => $request->input('MarkupPhp'),
        ]);
                    return redirect()->back()->with('success', 'Base prices updated successfully.');
    }

    public function localview($id)
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
        return view('price_monitoring_ls.view', compact('price_monitorings','prfFileUploads', 'activities', 'combinedLogs'));
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
    
}
