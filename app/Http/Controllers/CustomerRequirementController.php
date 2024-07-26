<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use App\CustomerRequirement;
use App\Client;
use App\User;
use App\PriceCurrency;
use App\NatureRequest;
use App\CrrNature;
use App\ProductApplication;
use App\SalesUser;
use Illuminate\Support\Facades\Auth;

class CustomerRequirementController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $search = $request->input('search');
        $customer_requirements = CustomerRequirement::with(['client', 'product_application'])
        ->where(function ($query) use ($search){
            $query->where('CrrNumber', 'LIKE', '%' . $search . '%')
            ->orWhere('DateCreated', 'LIKE', '%' . $search . '%')
            ->orWhere('DueDate', 'LIKE', '%' . $search . '%')
            ->orWhereHas('client', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            })
            ->orWhereHas('product_application', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            })
            ->orWhere('Recommendation', 'LIKE', '%' . $search . '%');
        })
        ->orderBy('id', 'desc')->paginate(25);
        $product_applications = ProductApplication::all();
        $clients = Client::all();
        $users = User::all();
        $price_currencies = PriceCurrency::all();
        $nature_requests = NatureRequest::all();
        return view('customer_requirements.index', compact('customer_requirements', 'clients', 'product_applications', 'users', 'price_currencies', 'nature_requests', 'search')); 
    }

    // Store
    public function store(Request $request)
    {
        $user = Auth::user(); 
        $salesUser = SalesUser::where('SalesUserId', $user->user_id)->first();
        $type = $salesUser->Type == 2 ? 'IS' : 'LS';
        $year = Carbon::parse($request->input('CreatedDate'))->format('y');
        $lastEntry = CustomerRequirement::where('CrrNumber', 'LIKE', "CRR-{$type}-%")
                    ->orderBy('id', 'desc')
                    ->first();
        $lastNumber = $lastEntry ? intval(substr($lastEntry->CrrNumber, -4)) : 0;
        $newIncrement = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $crrNo = "CRR-{$type}-{$year}-{$newIncrement}";

        $customerRequirementData = CustomerRequirement::create([
            'CrrNumber' => $crrNo,
            'CreatedDate' => $request->input('CreatedDate'),
            'DueDate' => $request->input('DueDate'),
            'ClientId' => $request->input('ClientId'),
            'ApplicationId' => $request->input('ApplicationId'),
            'PotentialVolume' => $request->input('PotentialVolume'),
            'TargetPrice' => $request->input('TargetPrice'),
            'Competitor' => $request->input('Competitor'),
            'PrimarySalesPersonId' => $request->input('PrimarySalesPersonId'),
            'SecondarySalesPersonId' => $request->input('SecondarySalesPersonId'),
            'Priority' => $request->input('Priority'),
            'DetailsOfRequirement' => $request->input('DetailsOfRequirement'),
            'Status' =>'10',
            'UnitOfMeasureId' => $request->input('UnitOfMeasureId'),
            'CurrencyId' => $request->input('CurrencyId'),
            'Progress' => '10',
            'CompetitorPrice' => $request->input('CompetitorPrice'),
            'RefCrrNumber' => $request->input('RefCrrNumber'),
            'RefRpeNumber' => $request->input('RefRpeNumber'),
        ]);
        foreach ($request->input('NatureOfRequestId') as $natureOfRequestId) {
                        CrrNature::create([
                            'CustomerRequirementId' => $customerRequirementData->id,
                            'NatureOfRequestId' => $natureOfRequestId
                        ]);
                    }
        
                    return redirect()->back()->with('success', 'Base prices updated successfully.');
    }
}
