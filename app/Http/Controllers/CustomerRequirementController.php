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
use App\Exports\CustomerRequirementExport;
use App\FileCrr;
use App\ProductApplication;
use App\SalesUser;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerRequirementController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $search = $request->input('search');
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'desc');
        
        $customer_requirements = CustomerRequirement::with(['client', 'product_application'])
        ->when($request->has('open') && $request->has('close'), function($query)use($request) {
            $query->whereIn('Status', [$request->open, $request->close]);
        })
        ->when($request->has('open') && !$request->has('close'), function($query)use($request) {
            $query->where('Status', $request->open);
        })
        ->when($request->has('close') && !$request->has('open'), function($query)use($request) {
            $query->where('Status', $request->close);
        })
        ->when($search, function ($query) use ($search){
            $query->where('CrrNumber', 'LIKE', '%' . $search . '%')
            ->orWhere('CreatedDate', 'LIKE', '%' . $search . '%')
            ->orWhere('DueDate', 'LIKE', '%' . $search . '%')
            ->orWhereHas('client', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            })
            ->orWhereHas('product_application', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            })
            ->orWhere('Recommendation', 'LIKE', '%' . $search . '%');
        })
        // ->orderBy('id', 'desc')
        ->orderBy($sort, $direction)
        ->paginate(10);

        $product_applications = ProductApplication::all();
        $clients = Client::all();
        $users = User::all();
        $price_currencies = PriceCurrency::all();
        $nature_requests = NatureRequest::all();
        $open = $request->open;
        $close = $request->close;
        return view('customer_requirements.index', compact('customer_requirements', 'clients', 'product_applications', 'users', 'price_currencies', 'nature_requests', 'search', 'open', 'close')); 
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
        if($request->has('NatureOfRequestId'))
        {
            foreach ($request->input('NatureOfRequestId') as $natureOfRequestId) {
                            CrrNature::create([
                                'CustomerRequirementId' => $customerRequirementData->id,
                                'NatureOfRequestId' => $natureOfRequestId
                            ]);
                        }
        }
        
        Alert::success('Successfully Save')->persistent('Dismiss');
        return back();
                    // return redirect()->back()->with('success', 'Base prices updated successfully.');
    }

    public function update(Request $request, $id)
    {
        $customerRequirements = CustomerRequirement::findOrFail($id);
        // $customerRequirements->DateCreated = date('Y-m-d');
        $customerRequirements->ClientId = $request->ClientId;
        $customerRequirements->Priority = $request->Priority;
        $customerRequirements->ApplicationId = $request->ApplicationId;
        $customerRequirements->DueDate = $request->DueDate;
        $customerRequirements->PotentialVolume = $request->PotentialVolume;
        $customerRequirements->UnitOfMeasureId = $request->UnitOfMeasureId;
        $customerRequirements->PrimarySalesPersonId = $request->PrimarySalesPersonId;
        $customerRequirements->TargetPrice = $request->TargetPrice;
        $customerRequirements->CurrencyId = $request->CurrencyId;
        $customerRequirements->SecondarySalesPersonId = $request->SecondarySalesPersonId;
        $customerRequirements->Competitor = $request->Competitor;
        $customerRequirements->CompetitorPrice = $request->CompetitorPrice;
        $customerRequirements->RefCrrNumber = $request->RefCrrNumber;
        $customerRequirements->RefRpeNumber = $request->RefRpeNumber;
        $customerRequirements->DetailsOfRequirement = $request->DetailsOfRequirement;
        if($request->has('NatureOfRequestId'))
        {
            $crrNature = CrrNature::where('CustomerRequirementId', $id)->delete();
            foreach($request->NatureOfRequestId as $key=>$natureOfRequestId)
            {
                $crrNature = new CrrNature;
                $crrNature->CustomerRequirementId = $id;
                $crrNature->NatureOfRequestId = $natureOfRequestId;
                $crrNature->save();
            }
        }
        $customerRequirements->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function view($id)
    {
        $customerRequirement = CustomerRequirement::with('client', 'product_application', 'progressStatus', 'crrNature', 'primarySales', 'secondarySales', 'priority', 'crrDetails')->findOrFail($id);
        $client = Client::get();
        $user = User::where('is_active', 1)->get();
        $currentUser = Auth::user();
        $product_applications = ProductApplication::get();
        $price_currencies = PriceCurrency::all();
        $nature_requests = NatureRequest::all();

        return view('customer_requirements.view_crr',
            array(
                'crr' => $customerRequirement,
                'clients' => $client,
                'users' => $user,
                'currentUser' => $currentUser,
                'product_applications' => $product_applications,
                'price_currencies' => $price_currencies,
                'nature_requests' => $nature_requests
            )
        );
    }

    public function addCrrFile(Request $request)
    {
        $request->validate([
            'crr_file' => 'mimes:pdf,docx,xlsx'
        ]);

        $crrFile = new FileCrr;
        $crrFile->Name = $request->file_name;
        $crrFile->CustomerRequirementId = $request->customer_requirements_id;
        if($request->has('is_confidential'))
        {
            $crrFile->IsConfidential = 1;
        }
        else
        {
            $crrFile->IsConfidential = 0;
        }

        if($request->has('is_for_review'))
        {
            $crrFile->IsForReview = 1;
        }
        else
        {
            $crrFile->IsForReview = 0;
        }

        $attachment = $request->file('crr_file');
        $name = time().'_'.$attachment->getClientOriginalName();
        $attachment->move(public_path().'/crr_files/', $name);

        $file_name = '/crr_files/'.$name;
        $crrFile->Path = $file_name;
        $crrFile->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function updateCrrFile(Request $request, $id)
    {
        $crrFile = FileCrr::findOrFail($id);
        $crrFile->Name = $request->file_name;

        if($request->has('is_confidential'))
        {
            $crrFile->IsConfidential = 1;
        }
        else
        {
            $crrFile->IsConfidential = 0;
        }

        if($request->has('is_for_review'))
        {
            $crrFile->IsForReview = 1;
        }
        else
        {
            $crrFile->IsForReview = 0;
        }

        if($request->has('crr_file'))
        {
            $attachment = $request->file('crr_file');
            $name = time().'_'.$attachment->getClientOriginalName();
            $attachment->move(public_path().'/crr_files/', $name);
    
            $file_name = '/crr_files/'.$name;
            $crrFile->Path = $file_name;
        }

        $crrFile->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function deleteCrrFile($id)
    {
        $crrFile = FileCrr::findOrFail($id);
        $crrFile->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }

    public function updateCrr(Request $request, $id)
    {
        $customerRequirement = CustomerRequirement::findOrFail($id);
        $customerRequirement->DdwNumber = $request->ddw_number;
        $customerRequirement->DateReceived = $request->date_received;
        $customerRequirement->DueDate = $request->due_date;
        $customerRequirement->Recommendation = $request->recommendation;
        $customerRequirement->Progress = $request->progress;
        $customerRequirement->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function export(Request $request)
    {
        return Excel::download(new CustomerRequirementExport($request->open, $request->close), 'Customer Requirement.xlsx');
    }

    public function delete($id)
    {
        $customerRequirement = CustomerRequirement::findOrFail($id);
        $customerRequirement->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }
}
