<?php

namespace App\Http\Controllers;
use App\ProductEvaluation;
use App\Client;
use App\PriceCurrency;
use App\ProductApplication;
use App\ProjectName;
use App\RequestProductEvaluation;
use App\SalesUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestProductEvaluationController extends Controller
{
    // List
    public function index()
    {   
        $request_product_evaluations = RequestProductEvaluation::with(['client', 'product_application'])->orderBy('id', 'desc')->paginate(25);
        $clients = Client::all();
        $users = User::all();
        $price_currencies = PriceCurrency::all();
        $project_names = ProjectName::all();

        $product_applications = ProductApplication::all();
        return view('product_evaluations.index', compact('request_product_evaluations','clients', 'product_applications', 'users',  'price_currencies', 'project_names')); 
    }

    public function store(Request $request)
    {
        $user = Auth::user(); 
        $salesUser = SalesUser::where('SalesUserId', $user->user_id)->first();
        $type = $salesUser->Type == 2 ? 'IS' : 'LS';
        $year = Carbon::parse($request->input('CreatedDate'))->format('y');
        $lastEntry = RequestProductEvaluation::where('RpeNumber', 'LIKE', "RPE-{$type}-%")
                    ->orderBy('id', 'desc')
                    ->first();
        $lastNumber = $lastEntry ? intval(substr($lastEntry->RpeNumber, -4)) : 0;
        $newIncrement = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $rpeNo = "RPE-{$type}-{$year}-{$newIncrement}";

        $productEvaluationData = RequestProductEvaluation::create([
            'RpeNumber' => $rpeNo,
            'CreatedDate' => $request->input('CreatedDate'),
            'DueDate' => $request->input('DueDate'),
            'ClientId' => $request->input('ClientId'),
            'ApplicationId' => $request->input('ApplicationId'),
            'PotentialVolume' => $request->input('PotentialVolume'),
            'TargetRawPrice' => $request->input('TargetRawPrice'),
            'ProjectNameId' => $request->input('ProjectNameId'),
            'PrimarySalesPersonId' => $request->input('PrimarySalesPersonId'),
            'SecondarySalesPersonId' => $request->input('SecondarySalesPersonId'),
            'Priority' => $request->input('Priority'),
            'AttentionTo' => $request->input('AttentionTo'),
            'UnitOfMeasureId' => $request->input('UnitOfMeasureId'),
            'CurrencyId' => $request->input('CurrencyId'),
            'SampleName' => $request->input('SampleName'),
            'Supplier' => $request->input('Supplier'),
            'ObjectiveForRpeProject' => $request->input('Objective'),
            'Status' =>'10',
            'Progress' => '10',
        ]);
                    return redirect()->back()->with('success', 'Base prices updated successfully.');
    }
}
