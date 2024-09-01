<?php

namespace App\Http\Controllers;

use App\Activity;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use App\CustomerRequirement;
use App\Client;
use App\CrrDetail;
use App\User;
use App\PriceCurrency;
use App\NatureRequest;
use App\CrrNature;
use App\CrrPersonnel;
use App\Exports\CustomerRequirementExport;
use App\FileCrr;
use App\ProductApplication;
use App\SalesApprovers;
use App\SalesUser;
use App\TransactionLogs;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Collective\Html\FormFacade as Form;

class CustomerRequirementController extends Controller
{
    // List
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'desc');
        $role = auth()->user()->role;
        $status = $request->query('status'); // Get the status from the query parameters

        $userId = Auth::id(); 
        $userByUser = Auth::user()->user_id; 
        // dd($userByUser);
        // Fetch customer requirements with applied filters
        $customer_requirements = CustomerRequirement::with(['client', 'product_application'])
            ->when($status, function($query) use ($status, $userId, $userByUser) {
                if ($status == '50') {
                    // When filtering by '50', include all cancelled status records
                    $query->where(function ($query) use ($userId, $userByUser) {
                        $query->where('Status', '50')
                            ->where(function($query) use ($userId, $userByUser) {
                                $query->where('PrimarySalesPersonId', $userId)
                                    ->orWhere('SecondarySalesPersonId', $userId)
                                    ->orWhere('PrimarySalesPersonId', $userByUser)
                                    ->orWhere('SecondarySalesPersonId', $userByUser);
                            });
                    });
                } else {
                    // Apply status filter if it's not '50'
                    $query->where('Status', $status);
                }
            })
            ->when($request->has('open') && $request->has('close'), function($query) use ($request) {
                $query->whereIn('Status', [$request->open, $request->close]);
            })
            ->when($request->has('open') && !$request->has('close'), function($query) use ($request) {
                $query->where('Status', $request->open);
            })
            ->when($request->has('close') && !$request->has('open'), function($query) use ($request) {
                $query->where('Status', $request->close);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function($query) use ($search) {
                    $query->where('CrrNumber', 'LIKE', '%' . $search . '%')
                    ->orWhere('CreatedDate', 'LIKE', '%' . $search . '%')
                    ->orWhere('DueDate', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('product_application', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('primarySales', function($query) use ($search) {
                        $query->where('full_name', 'LIKE', '%'.$search.'%');
                    })
                    ->orWhereHas('primarySalesById', function($query) use ($search) {
                        $query->where('full_name', 'LIKE', '%'.$search.'%');
                    })
                    ->orWhere('Recommendation', 'LIKE', '%' . $search . '%');
                });
            })
            ->when($role->type, function($q) use ($role) {
                if ($role->type == "IS") {
                    $q->where('CrrNumber', 'LIKE', "%CRR-IS%");
                } elseif ($role->type == "LS") {
                    $q->where('CrrNumber', 'LIKE', '%CRR-LS%');
                }
            })
            ->orderBy($sort, $direction)
            ->paginate($request->entries ?? 10);

        // Fetch related data for filters and dropdowns
        $product_applications = ProductApplication::all();
        $clients = Client::all();
        $users = User::all();
        $price_currencies = PriceCurrency::all();
        $nature_requests = NatureRequest::all();

        // Fetch request parameters for view
        $open = $request->open;
        $close = $request->close;
        $entries = $request->entries;
        $refCode = $this->refCode();

        // Return view with all necessary data
        return view('customer_requirements.index', compact(
            'customer_requirements', 
            'clients', 
            'product_applications', 
            'users', 
            'price_currencies', 
            'nature_requests', 
            'search', 
            'open', 
            'close', 
            'entries', 
            'refCode'
        ));
    }

    // Store
    public function store(Request $request)
    {
        // $salesUser = SalesUser::where('SalesUserId', $user->user_id)->first();
        // $type = $salesUser->Type == 2 ? 'IS' : 'LS';
        // $year = Carbon::parse($request->input('CreatedDate'))->format('y');
        // $lastEntry = CustomerRequirement::where('CrrNumber', 'LIKE', "CRR-{$type}-%")
        //             ->orderBy('id', 'desc')
        //             ->first();
        // $lastNumber = $lastEntry ? intval(substr($lastEntry->CrrNumber, -4)) : 0;
        // $newIncrement = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        // $crrNo = "CRR-{$type}-{$year}-{$newIncrement}";

        $user = Auth::user(); 
        if (($user->department_id == 5) || ($user->department_id == 38))
        {
            $type = "";
            $year = date('y');
            if ($user->department_id == 5)
            {
                $type = "IS";
                $crrList = CustomerRequirement::where('CrrNumber', 'LIKE', '%CRR-IS%')->orderBy('id', 'desc')->first();
                $count = substr($crrList->CrrNumber, 10);
                $totalCount = $count + 1;
                
                $crrNo = "CRR-".$type.'-'.$year.'-'.$totalCount;
            }

            if ($user->department_id == 38)
            {
                $type = "LS";
                $crrList = CustomerRequirement::where('CrrNumber', 'LIKE', '%CRR-LS%')->orderBy('id', 'desc')->first();
                $count = substr($crrList->CrrNumber, 10);
                $totalCount = $count + 1;
                
                $crrNo = "CRR-".$type.'-'.$year.'-'.$totalCount;
            }
        }

        $customerRequirementData = CustomerRequirement::create([
            'CrrNumber' => $crrNo,
            // 'CreatedDate' => $request->input('CreatedDate'),
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
            'RefCode' => $request->RefCode
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
        // $customerRequirements->Status = $request->Status;
        $customerRequirements->RefCode = $request->RefCode;
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
        $rnd_personnel = User::whereIn('department_id', [15, 42])->whereNotIn('id', [auth()->user()->id])->get();
        $refCode = $this->refCode();

        return view('customer_requirements.view_crr',
            array(
                'crr' => $customerRequirement,
                'clients' => $client,
                'users' => $user,
                'currentUser' => $currentUser,
                'product_applications' => $product_applications,
                'price_currencies' => $price_currencies,
                'nature_requests' => $nature_requests,
                'rnd_personnel' => $rnd_personnel,
                'refCode' => $refCode
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
        $customerRequirement->Status = $request->Status;
        // $customerRequirement->Progress = $request->progress;
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

    public function refCode()
    {
        return array(
            'RND' => 'R&D',
            'QCD-WHI' => 'QCD-WHI',
            'QCD-PBI' => 'QCD-PBI',
            'QCD-MRDC' => 'QCD-MRDC',
            'QCD-CCC' => 'QCD-CCC'
        );
    }

    public function closeRemarks(Request $request, $id)
    {
        $customerRequirement = CustomerRequirement::findOrFail($id);
        // $customerRequirement->CloseRemarks = $request->close_remarks;
        $customerRequirement->Status = 30;
        $customerRequirement->save();

        $transactionLogs = new TransactionLogs;
        $transactionLogs->Type = 10;
        $transactionLogs->TransactionId = $customerRequirement->id;
        $transactionLogs->ActionDate = date('Y-m-d h:i:s');
        $transactionLogs->UserId = auth()->user()->id;
        $transactionLogs->Details = $request->close_remarks;
        $transactionLogs->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function cancelRemarks(Request $request, $id)
    {
        $customerRequirement = CustomerRequirement::findOrFail($id);
        $customerRequirement->CancelRemarks = $request->cancel_remarks;
        $customerRequirement->Status = 50;
        $customerRequirement->save();

        $transactionLogs = new TransactionLogs;
        $transactionLogs->Type = 10;
        $transactionLogs->TransactionId = $customerRequirement->id;
        $transactionLogs->ActionDate = date('Y-m-d h:i:s');
        $transactionLogs->UserId = auth()->user()->id;
        $transactionLogs->Details = $request->cancel_remarks;
        $transactionLogs->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function acceptCrr(Request $request, $id)
    {
        $crr = CustomerRequirement::findOrFail($id);

        if ($request->action == "approved_to_sales")
        {
            $crr->Progress = 20;
            $crr->AcceptRemarks = $request->accept_remarks;
            $crr->ApprovedBy = auth()->user()->id;
            $crr->save();

            $transactionLogs = new TransactionLogs;
            $transactionLogs->Type = 10;
            $transactionLogs->TransactionId = $crr->id;
            $transactionLogs->ActionDate = date('Y-m-d h:i:s');
            $transactionLogs->UserId = auth()->user()->id;
            $transactionLogs->Details = $request->accept_remarks;
            $transactionLogs->save();
        }
        elseif($request->action == "approved_to_RND")
        {
            $crr->Progress = 30;
            $crr->AcceptRemarks = $request->accept_remarks;
            $crr->ApprovedBy = auth()->user()->id;
            $crr->save();

            $transactionLogs = new TransactionLogs;
            $transactionLogs->Type = 10;
            $transactionLogs->TransactionId = $crr->id;
            $transactionLogs->ActionDate = date('Y-m-d h:i:s');
            $transactionLogs->UserId = auth()->user()->id;
            $transactionLogs->Details = $request->accept_remarks;
            $transactionLogs->save();
        }
        elseif($request->action == "approved_to_QCD-MRDC")
        {
            $crr->Progress = 30;
            $crr->AcceptRemarks = $request->accept_remarks;
            $crr->ApprovedBy = auth()->user()->id;
            $crr->save();

            $transactionLogs = new TransactionLogs;
            $transactionLogs->Type = 10;
            $transactionLogs->TransactionId = $crr->id;
            $transactionLogs->ActionDate = date('Y-m-d h:i:s');
            $transactionLogs->UserId = auth()->user()->id;
            $transactionLogs->Details = $request->accept_remarks;
            $transactionLogs->save();
        }

        Alert::success('Approved to RND')->persistent('Dismiss');
        return back();
    }

    public function openStatus(Request $request, $id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Status = 10;
        $crr->save();

        Alert::success('The status are now open')->persistent('Dismiss');
        return back();
    }

    public function rndReceived(Request $request, $id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Progress = 35;
        $crr->DateReceived = date('Y-m-d h:i:s');
        $crr->save();

        Alert::success('The status are now received')->persistent('Dismiss');
        return back();
    }

    public function startCrr(Request $request, $id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Progress = 50;
        $crr->save();

        Alert::success('Successfully Start')->persistent('Dismiss');
        return back();
    }

    public function pauseCrr(Request $request, $id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Progress = 55;
        $crr->save();

        $transactionLogs = new TransactionLogs;
        $transactionLogs->Type = 10;
        $transactionLogs->TransactionId = $crr->id;
        $transactionLogs->ActionDate = date('Y-m-d h:i:s');
        $transactionLogs->UserId = auth()->user()->id;
        $transactionLogs->Details = $request->pause_remarks;
        $transactionLogs->save();

        Alert::success('Successfully Paused')->persistent('Dismiss');
        return back();
    }

    public function submitCrr(Request $request, $id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Progress = 57;
        $crr->save();

        Alert::success('Successfully Submitted')->persistent('Dismiss');
        return back();
    }

    public function submitFinalCrr(Request $request, $id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Progress = 81;
        $crr->save();

        Alert::success('Successfully Final Review')->persistent('Dismiss');
        return back();
    }

    public function completeCrr(Request $request, $id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Progress = 60;
        $crr->DateCompleted = date('Y-m-d h:i:s');
        $crr->save();

        Alert::success('Successfully Completed')->persistent('Dismiss');
        return back();
    }

    public function addSupplementary(Request $request)
    {
        $crrDetails = new CrrDetail;
        $crrDetails->CustomerRequirementId = $request->customer_requirement_id;
        $crrDetails->UserId = $request->user_id;
        $crrDetails->DetailsOfRequirement = $request->details;
        $crrDetails->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function updateSupplementary(Request $request, $id)
    {
        $crrDetails = CrrDetail::findOrFail($id);
        $crrDetails->CustomerRequirementId = $request->customer_requirement_id;
        $crrDetails->UserId = $request->user_id;
        $crrDetails->DetailsOfRequirement = $request->details;
        $crrDetails->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function deleteSupplementary(Request $request, $id)
    {
        $crrDetails = CrrDetail::findOrFail($id);
        $crrDetails->delete();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function addPersonnel(Request $request)
    {
        $personnel = new CrrPersonnel;
        $personnel->CustomerRequirementId = $request->customer_requirement_id;
        $personnel->PersonnelUserId = $request->personnel;
        $personnel->save(); 

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }
    
    public function updatePersonnel(Request $request, $id)
    {
        $personnel = CrrPersonnel::findOrFail($id);
        $personnel->CustomerRequirementId = $request->customer_requirement_id;
        $personnel->PersonnelUserId = $request->personnel;
        $personnel->save(); 

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function deletePersonnel($id)
    {
        $personnel = CrrPersonnel::findOrFail($id);
        $personnel->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }
    
    public function refreshUserApprover(Request $request)
    {
        $user = User::where('id', $request->ps)->first();
        
        if ($user != null)
        {
            if($user->salesApproverById)
            {
                $approvers = $user->salesApproverById->pluck('SalesApproverId')->toArray();
                $sales_approvers = User::whereIn('id', $approvers)->pluck('full_name', 'id')->toArray();

                return Form::select('SecondarySalesPersonId', $sales_approvers, null, array('class' => 'form-control'));
            }
            elseif($user->salesApproverByUserId)
            {
                $approvers = $user->salesApproverByUserId->pluck('SalesApproverId')->toArray();
                $sales_approvers = User::whereIn('id', $approvers)->pluck('full_name', 'id')->toArray();

                return Form::select('SecondarySalesPersonId', $sales_approvers, null, array('class' => 'form-control'));
            }
        }

        return "";
    }

    public function returnToSales($id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Progress = 10;
        $crr->save(); 

        Alert::success('Successfully return to sales')->persistent('Dismiss');
        return back();
    }

    public function returnToRnd($id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Progress = 50;
        $crr->save(); 

        Alert::success('Successfully return to rnd')->persistent('Dismiss');
        return back();
    }

    public function salesAccepted($id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Progress = 70;
        $crr->save(); 

        Alert::success('Sales Accepted')->persistent('Dismiss');
        return back();
    }
}
