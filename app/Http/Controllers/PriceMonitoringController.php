<?php

namespace App\Http\Controllers;

use App\Client;
use App\PaymentTerms;
use App\PrfFile;
use App\PriceMonitoring;
use App\PriceRequestProduct;
use App\Product;
use App\SalesUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('price_monitoring.view', compact('price_monitorings','prfFileUploads'));
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
}
