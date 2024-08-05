<?php

namespace App\Http\Controllers;
use App\ProductEvaluation;
use App\Client;
use App\PriceCurrency;
use App\ProductApplication;
use App\ProjectName;
use App\RequestProductEvaluation;
use App\SalesUser;
use App\TransactionApproval;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestProductEvaluationController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $search = $request->input('search');
        $request_product_evaluations = RequestProductEvaluation::with(['client', 'product_application'])
        ->where(function ($query) use ($search){
            $query->where('RpeNumber', 'LIKE', '%' . $search . '%')
            ->orWhere('CreatedDate', 'LIKE', '%' . $search . '%')
            ->orWhere('DueDate', 'LIKE', '%' . $search . '%')
            ->orWhereHas('client', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            })
            ->orWhereHas('product_application', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            })
            ->orWhere('RpeResult', 'LIKE', '%' . $search . '%');
        })
        ->orderBy('id', 'desc')->paginate(25);
        $clients = Client::all();
        $users = User::all();
        $price_currencies = PriceCurrency::all();
        $project_names = ProjectName::all();

        $product_applications = ProductApplication::all();
        return view('product_evaluations.index', compact('request_product_evaluations','clients', 'product_applications', 'users',  'price_currencies', 'project_names', 'search')); 
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
            'ObjectiveForRpeProject' => $request->input('ObjectiveForRpeProject'),
            'Status' =>'10',
            'Progress' => '10',
        ]);
                    return redirect()->back()->with('success', 'Base prices updated successfully.');
    }
    public function update(Request $request, $id)
    {
        $rpe = RequestProductEvaluation::with(['client', 'product_application'])->findOrFail($id);
        $rpe->DueDate = $request->input('DueDate');
        $rpe->ClientId = $request->input('ClientId');
        $rpe->ApplicationId = $request->input('ApplicationId');
        $rpe->PotentialVolume = $request->input('PotentialVolume');
        $rpe->TargetRawPrice = $request->input('TargetRawPrice');
        $rpe->ProjectNameId = $request->input('ProjectNameId');
        $rpe->PrimarySalesPersonId = $request->input('PrimarySalesPersonId');
        $rpe->SecondarySalesPersonId = $request->input('SecondarySalesPersonId');
        $rpe->Priority = $request->input('Priority');
        $rpe->AttentionTo = $request->input('AttentionTo');
        $rpe->UnitOfMeasureId = $request->input('UnitOfMeasureId');
        $rpe->CurrencyId = $request->input('CurrencyId');
        $rpe->SampleName = $request->input('SampleName');
        $rpe->Supplier = $request->input('Supplier');
        $rpe->ObjectiveForRpeProject = $request->input('ObjectiveForRpeProject');
        $rpe->save();
        return redirect()->back()->with('success', 'RPE updated successfully');
    }

    public function destroy($id)
    {
        try {
            $basePrice = RequestProductEvaluation::findOrFail($id); 
            $basePrice->delete();  
            return response()->json(['success' => true, 'message' => 'Request deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete Request.'], 500);
        }
    }

    public function view($id)
    {
        $requestEvaluation = RequestProductEvaluation::with(['client', 'product_application'])->findOrFail($id);
        $rpeNumber = $requestEvaluation->id;
        $clientId = $requestEvaluation->ClientId;
        $rpeTransactionApprovals  = TransactionApproval::where('TransactionId', $id)
        ->where('Type', '20')
        ->get();
        
       
        return view('product_evaluations.view', compact('requestEvaluation', 'rpeTransactionApprovals'));
    }
}
