<?php

namespace App\Http\Controllers;

use App\CcPackaging;
use App\CcProductQuality;
use App\CcDeliveryHandling;
use App\CcFile;
use App\CcOthers;
use App\ComplaintFile;
use App\Country;
use App\ConcernDepartment;
use App\CustomerComplaint;
use App\CustomerComplaint2;
use App\Notifications\EmailDepartment;
use App\CcObjectiveFile;
use App\CcVerificationFile;
use Illuminate\Support\Facades\App;
use App\Mail\CustomerComplaintMail;
use App\Mail\AssignCcDepartmentMail;
use App\Mail\InvestigationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerComplaint2Controller extends Controller
{
    public function index()
    {
        $countries = Country::get();
        $concern_department = ConcernDepartment::get();

        $year = date('y') . '4'; 
        
        $latestCc = CustomerComplaint2::whereYear('created_at', date('Y'))
                        ->orderBy('CcNumber', 'desc')
                        ->first();
        
        if ($latestCc) {
            $latestSeries = (int) substr($latestCc->CcNumber, -4);
            $newSeries = str_pad($latestSeries + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newSeries = '0001';
        }

        $newCcNo = 'CCF-' . $year . '-' . $newSeries;

        return view('customer_service.customer_complaint', compact('countries', 'concern_department', 'newCcNo'));
    }

    public function list(Request $request) 
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'CcNumber');
        $direction = $request->get('direction', 'asc');
        $fetchAll = filter_var($request->input('fetch_all', false), FILTER_VALIDATE_BOOLEAN);
        $role = auth()->user()->role;
        $entries = $request->input('number_of_entries', 10);
        $progress = $request->query('progress'); // Get the status from the query parameters
        $status = $request->query('status'); // Get the status from the query parameters

        $userId = Auth::id(); 
        $userByUser = optional(Auth::user())->user_id; 

        $year = date('y') . '4'; 

        $latestCc = CustomerComplaint2::whereYear('created_at', date('Y'))
                            ->orderBy('CcNumber', 'desc')
                            ->first();

        if ($latestCc) {
            $latestSeries = (int) substr($latestCc->CcNumber, -4);
            $newSeries = str_pad($latestSeries + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newSeries = '0001';
        }

        $newCcNo = 'CCF-' . $year . '-' . $newSeries;

        // Set default for open status if not present in the request
        $open = $request->input('open');
        $close = $request->input('close');

        $customerComplaint = CustomerComplaint2::when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('CcNumber', 'LIKE', '%' . $search . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $search . '%')
                    ->orWhere('CompanyName', 'LIKE', '%' . $search . '%')
                    ->orWhere('ContactName', 'LIKE', '%' . $search . '%')
                    ->orWhere('Description', 'LIKE', '%' . $search . '%')
                    ->orWhere('CustomerRemarks', 'LIKE', '%' . $search . '%')
                    ->orWhere('Status', 'LIKE', '%' . $search . '%');
            });
        })
        ->when($request->input('open') && $request->input('close'), function ($query) use ($request) {
            $query->whereIn('Status', [$request->input('open'), $request->input('close')]);
        })
        ->when($request->input('open') && !$request->input('close'), function ($query) use ($request) {
            $query->where('Status', $request->input('open'));
        })
        ->when($request->input('close') && !$request->input('open'), function ($query) use ($request) {
            $query->where('Status', $request->input('close'));
        })
        ->when(isset($role) && in_array($role->type, ['RND', 'QCD-WHI', 'QCD-PBI', 'QCD-MRDC', 'QCD-CCC']) && in_array($role->name, ['Staff L1', 'Staff L2']), function ($q) {
            $q->whereHas('concerned', function($q) {
                $q->where('Department',  auth()->user()->role->type);
            });
        })
        ->when($progress, function($query) use ($progress, $userId, $userByUser) {
            if ($progress == '20') {
                $query->where('Progress', '20')
                    ->whereHas('salesapprovers', function ($query) use ($userId) {
                        $query->where('SalesApproverId', $userId);
                    });
            } else {
                $query->where('Progress', $progress);
            }
        })
        ->orWhere(function ($query) use ($userId) {
            // Include entries where 'ReceivedBy' is the same as the 'userId' in the 'salesapprovers' table
            $query->where('Progress', '20')
                ->whereHas('salesapprovers', function ($query) use ($userId) {
                    $query->where('SalesApproverId', $userId);
                });
        })
        ->orderBy($sort, $direction);
    

        // Fetch data based on `fetchAll` flag and return
        if ($fetchAll) {
            $data = $customerComplaint->get();
            return response()->json($data);
        } else {
            $data = $customerComplaint->paginate($entries);
            return view('customer_service.cc_list', [
                'search' => $search,
                'data' => $data,
                'open' => $open,
                'close' => $close,
                'fetchAll' => $fetchAll,
                'entries' => $entries,
                'newCcNo' => $newCcNo,
                'progress' => $progress
            ]);
        }
    }

    public function store(Request $request)
    {
        $customerComplaint = CustomerComplaint2::create([
            'CompanyName' => $request->CompanyName,
            'CcNumber' => $request->CcNumber,
            'ContactName' => $request->ContactName,
            'Email' => $request->Email,
            // 'Address' => $request->Address,
            'Country' => $request->Country,
            'Telephone' => $request->Telephone,
            // 'Moc' => $request->Moc,
            // 'QualityClass' => $request->QualityClass,
            // 'ProductName' => $request->ProductName,
            // 'Description' => $request->Description,
            // 'Currency' => $request->Currency,
            'CustomerRemarks' => $request->CustomerRemarks,
            // 'SiteConcerned' => $request->SiteConcerned,
            // 'Department' => $request->Department,
            'Status' => '10',
            'Progress' => '10'
        ]);

        $attachments = [];
        if ($request->hasFile('Path') && is_array($request->file('Path'))) {
            foreach ($request->file('Path') as $file) {
                if ($file->isValid()) {
                    $ccFiles = new ComplaintFile();
                    $ccFiles->CcId = $customerComplaint->id;

                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('cc_files', $fileName, 'public'); 
                    $ccFiles->Path = $filePath; 
                    $ccFiles->save();

                    $attachments[] = $filePath;
                }
            }
        }
        // CcProductQuality::create([
        //     'CcId' => $customerComplaint->id,
        //     'Pn1' => $request->Pn1,
        //     'ScNo1' => $request->ScNo1,
        //     'SoNo1' => $request->SoNo1,
        //     'Quantity1' => $request->Quantity1,
        //     'LotNo1' => $request->LotNo1,
        //     'Pn2' => $request->Pn2,
        //     'ScNo2' => $request->ScNo2,
        //     'SoNo2' => $request->SoNo2,
        //     'Quantity2' => $request->Quantity2,
        //     'LotNo2' => $request->LotNo2,
        //     'Pn3' => $request->Pn3,
        //     'ScNo3' => $request->ScNo3,
        //     'SoNo3' => $request->SoNo3,
        //     'Quantity3' => $request->Quantity3,
        //     'LotNo3' => $request->LotNo3,
        //     'Pn4' => $request->Pn4,
        //     'ScNo4' => $request->ScNo4,
        //     'SoNo4' => $request->SoNo4,
        //     'Quantity4' => $request->Quantity4,
        //     'LotNo4' => $request->LotNo4,
        //     'Pn5' => $request->Pn5,
        //     'ScNo5' => $request->ScNo5,
        //     'SoNo5' => $request->SoNo5,
        //     'Quantity5' => $request->Quantity5,
        //     'LotNo5' => $request->LotNo5,
        //     'Pn6' => $request->Pn6,
        //     'ScNo6' => $request->ScNo6,
        //     'SoNo6' => $request->SoNo6,
        //     'Quantity6' => $request->Quantity6,
        //     'LotNo6' => $request->LotNo6,
        // ]);

        // CcPackaging::create([
        //     'CcId' => $customerComplaint->id,
        //     'PackPn1' => $request->PackPn1,
        //     'PackScNo1' => $request->PackScNo1,
        //     'PackSoNo1' => $request->PackSoNo1,
        //     'PackQuantity1' => $request->PackQuantity1,
        //     'PackLotNo1' => $request->PackLotNo1,
        //     'PackPn2' => $request->PackPn2,
        //     'PackScNo2' => $request->PackScNo2,
        //     'PackSoNo2' => $request->PackSoNo2,
        //     'PackQuantity2' => $request->PackQuantity2,
        //     'PackLotNo2' => $request->PackLotNo2,
        //     'PackPn3' => $request->PackPn3,
        //     'PackScNo3' => $request->PackScNo3,
        //     'PackSoNo3' => $request->PackSoNo3,
        //     'PackQuantity3' => $request->PackQuantity3,
        //     'PackLotNo3' => $request->PackLotNo3,
        //     'PackPn4' => $request->PackPn4,
        //     'PackScNo4' => $request->PackScNo4,
        //     'PackSoNo4' => $request->PackSoNo4,
        //     'PackQuantity4' => $request->PackQuantity4,
        //     'PackLotNo4' => $request->PackLotNo4
        // ]);

        // CcDeliveryHandling::create([
        //     'CcId' => $customerComplaint->id,
        //     'DhPn1' => $request->DhPn1,
        //     'DhScNo1' => $request->DhScNo1,
        //     'DhSoNo1' => $request->DhSoNo1,
        //     'DhQuantity1' => $request->DhQuantity1,
        //     'DhLotNo1' => $request->DhLotNo1,
        //     'DhPn2' => $request->DhPn2,
        //     'DhScNo2' => $request->DhScNo2,
        //     'DhSoNo2' => $request->DhSoNo2,
        //     'DhQuantity2' => $request->DhQuantity2,
        //     'DhLotNo2' => $request->DhLotNo2,
        //     'DhPn3' => $request->DhPn3,
        //     'DhScNo3' => $request->DhScNo3,
        //     'DhSoNo3' => $request->DhSoNo3,
        //     'DhQuantity3' => $request->DhQuantity3,
        //     'DhLotNo3' => $request->DhLotNo3
        // ]);

        // CcOthers::create([
        //     'CcId' => $customerComplaint->id,
        //     'OthersPn1' => $request->OthersPn1,
        //     'OthersScNo1' => $request->OthersScNo1,
        //     'OthersSoNo1' => $request->OthersSoNo1,
        //     'OthersQuantity1' => $request->OthersQuantity1,
        //     'OthersLotNo1' => $request->OthersLotNo1,
        //     'OthersPn2' => $request->OthersPn2,
        //     'OthersScNo2' => $request->OthersScNo2,
        //     'OthersSoNo2' => $request->OthersSoNo2,
        //     'OthersQuantity2' => $request->OthersQuantity2,
        //     'OthersLotNo2' => $request->OthersLotNo2,
        //     'OthersPn3' => $request->OthersPn3,
        //     'OthersScNo3' => $request->OthersScNo3,
        //     'OthersSoNo3' => $request->OthersSoNo3,
        //     'OthersQuantity3' => $request->OthersQuantity3,
        //     'OthersLotNo3' => $request->OthersLotNo3,
        //     'OthersPn4' => $request->OthersPn4,
        //     'OthersScNo4' => $request->OthersScNo4,
        //     'OthersSoNo4' => $request->OthersSoNo4,
        //     'OthersQuantity4' => $request->OthersQuantity4,
        //     'OthersLotNo4' => $request->OthersLotNo4
        // ]);

        // dd($customerComplaint);
        // return response()->json(['success' => 'You submitted the form successfully..$customerComplaint->id']);
        // return response()->json([
        //     'success' => 'You submitted the form successfully. ID: ' . $request->input('CcNumber')
        // ]);

        // Mail::to($request->Email)
        //     ->cc('ict.engineer@wgroup.com.ph')
        //     ->send(new CustomerComplaintMail($customerComplaint, $attachments));

        // Send to main recipient WITHOUT button
        Mail::to($customerComplaint['Email'])
        ->send(new CustomerComplaintMail($customerComplaint, $attachments, false));

        // Send to CC recipients WITH button
        Mail::to(['crista.bautista@rico.com.ph']) // CC emails here
        ->send(new CustomerComplaintMail($customerComplaint, $attachments, true));
        
        return response()->json(['success' => 'Your customer complaint has been submitted successfully!']);
    }

    public function update(Request $request, $id)
    {
        $data = CustomerComplaint2::findOrFail($id);
        // $data->RecurringIssue = $request->RecurringIssue;
        $data->PreviousCCF = $request->PreviousCCF;
        $data->ImmediateAction = $request->ImmediateAction;
        $data->ObjectiveEvidence = $request->ObjectiveEvidence;
        $data->Investigation = $request->Investigation;
        $data->CorrectiveAction = $request->CorrectiveAction;
        $data->ActionObjectiveEvidence = $request->ActionObjectiveEvidence;
        $data->ActionResponsible = auth()->user()->id;
        $data->ActionDate = now();
        $data->Progress = 80;
        $data->save();

        $attachments = [];
        if ($request->hasFile('Path')) {
            foreach ($request->file('Path') as $file) {
                if ($file->isValid()) {
                    $ccFile = new CcObjectiveFile();
                    $ccFile->CcId = $data->id;
    
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('cc_files', $fileName, 'public');
                    
                    $ccFile->Path = $filePath;
                    $ccFile->save();

                    $attachments[] = $filePath;
                }
            }
        }

        Mail::to(['crista.bautista@rico.com.ph']) // CC emails here
            ->send(new InvestigationMail($data, $attachments, true));
        
        return response()->json([
            'success' => true,
            'message' => 'Customer complaint investigation has been successfully updated.'
        ]);
    }

    public function view($id)
    {
        $data = CustomerComplaint2::with('concerned', 'country', 'product_quality', 'packaging', 'delivery_handling', 'others', 'files', 'objective')->findOrFail($id);
        $concern_department = ConcernDepartment::all();

        return view('customer_service.cc_view', compact('data','concern_department'));
    }

    public function acceptance(Request $request, $id)
    {
        $data = CustomerComplaint2::findOrFail($id);
        $data->Acceptance = $request->Acceptance;
        $data->Claims = $request->Claims;
        $data->Shipment = $request->Shipment;
        $data->CnNumber = $request->CnNumber;
        $data->ShipmentDate = $request->ShipmentDate;
        $data->AmountIncurred = $request->AmountIncurred;
        $data->ShipmentCost = $request->ShipmentCost;
        $data->Progress = 60;
        $data->save();

        if ($request->hasFile('Path')) {
            foreach ($request->file('Path') as $file) {
                if ($file->isValid()) {
                    $verificationFile = new CcVerificationFile();
                    $verificationFile->CcId = $data->id;
    
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('cc_files', $fileName, 'public');
                    
                    $verificationFile->Path = $filePath;
                    $verificationFile->save();
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Customer complaint acceptance has been successfully updated.'
        ]);
    }

    public function received($id)
    {
        $data = CustomerComplaint2::findOrFail($id);
        $data->ReceivedBy = auth()->user()->id;
        $data->DateReceived = now();
        $data->Progress = 20;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Customer complaint has been successfully received.'
        ]);
    }

    public function noted($id)
    {
        $data = CustomerComplaint2::findOrFail($id);
        $data->NotedBy = auth()->user()->id;
        $data->DateNoted = now();
        $data->Progress = 30;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Customer complaint has been successfully updated.'
        ]);
    }

    public function approved($id)
    {
        $data = CustomerComplaint2::findOrFail($id);
        $data->ApprovedBy = auth()->user()->id;
        $data->Progress = 40;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Customer satisfaction has been successfully approved.'
        ]);
    }
    
    public function closed($id)
    {
        $data = CustomerComplaint2::findOrFail($id);
        $data->ClosedBy = auth()->user()->id;
        $data->ClosedDate = now();
        $data->Status = 30;
        $data->Progress = 70;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Customer complaint has been successfully closed.'
        ]);
    }

    public function ccupdate(Request $request, $id)
    {
        
        $data = CustomerComplaint2::findOrFail($id);
        $data->RecurringIssue = $request->RecurringIssue;
        $data->Description = $request->Description;
        $data->Currency = $request->Currency;
        $data->NcarIssuance = $request->NcarIssuance;
        $data->save();

        // Ensure product quality record exists
        $product_quality = CcProductQuality::where('CcId', $id)->first();
        if (!$product_quality) {
            $product_quality = new CcProductQuality();
            $product_quality->CcId = $id; // Assign foreign key
        }

        for ($i = 1; $i <= 6; $i++) {
            $product_quality->{'Pn' . $i} = $request->{'Pn' . $i};
            $product_quality->{'ScNo' . $i} = $request->{'ScNo' . $i};
            $product_quality->{'SoNo' . $i} = $request->{'SoNo' . $i};
            $product_quality->{'Quantity' . $i} = $request->{'Quantity' . $i};
            $product_quality->{'LotNo' . $i} = $request->{'LotNo' . $i};
        }
        $product_quality->save();

        // Ensure packaging record exists
        $packaging = CcPackaging::where('CcId', $id)->first();
        if (!$packaging) {
            $packaging = new CcPackaging();
            $packaging->CcId = $id;
        }

        for ($i = 1; $i <= 4; $i++) {
            $packaging->{'PackPn' . $i} = $request->{'PackPn' . $i};
            $packaging->{'PackScNo' . $i} = $request->{'PackScNo' . $i};
            $packaging->{'PackSoNo' . $i} = $request->{'PackSoNo' . $i};
            $packaging->{'PackQuantity' . $i} = $request->{'PackQuantity' . $i};
            $packaging->{'PackLotNo' . $i} = $request->{'PackLotNo' . $i};
        }
        $packaging->save();

        // Ensure delivery handling record exists
        $delivery_handling = CcDeliveryHandling::where('CcId', $id)->first();
        if (!$delivery_handling) {
            $delivery_handling = new CcDeliveryHandling();
            $delivery_handling->CcId = $id;
        }

        for ($i = 1; $i <= 3; $i++) {
            $delivery_handling->{'DhPn' . $i} = $request->{'DhPn' . $i};
            $delivery_handling->{'DhScNo' . $i} = $request->{'DhScNo' . $i};
            $delivery_handling->{'DhSoNo' . $i} = $request->{'DhSoNo' . $i};
            $delivery_handling->{'DhQuantity' . $i} = $request->{'DhQuantity' . $i};
            $delivery_handling->{'DhLotNo' . $i} = $request->{'DhLotNo' . $i};
        }
        $delivery_handling->save();

        // Ensure others record exists
        $others = CcOthers::where('CcId', $id)->first();
        if (!$others) {
            $others = new CcOthers();
            $others->CcId = $id;
        }

        for ($i = 1; $i <= 4; $i++) {
            $others->{'OthersPn' . $i} = $request->{'OthersPn' . $i};
            $others->{'OthersScNo' . $i} = $request->{'OthersScNo' . $i};
            $others->{'OthersSoNo' . $i} = $request->{'OthersSoNo' . $i};
            $others->{'OthersQuantity' . $i} = $request->{'OthersQuantity' . $i};
            $others->{'OthersLotNo' . $i} = $request->{'OthersLotNo' . $i};
        }
        $others->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        // return back();
        return redirect()->back()->with('openModalId', $id);
    }

    public function assign(Request $request, $id)
    {
        // dd($request->all());
        $customer_complaint = CustomerComplaint2::findOrFail($id);
        $customer_complaint->QualityClass = $request->QualityClass;
        $customer_complaint->ProductName = $request->ProductName;
        $customer_complaint->Department = $request->Department;
        $customer_complaint->SiteConcerned = $request->SiteConcerned;
        $customer_complaint->Progress = 50;
        $customer_complaint->save();

        $department = ConcernDepartment::where('Name', $request->Department)->firstOrFail();

        $attachments = [];
        if ($request->hasFile('Path') && is_array($request->file('Path'))) {
            foreach ($request->file('Path') as $file) {
                if ($file->isValid()) {
                    $ccFiles = new CcFile();
                    $ccFiles->CcId = $customer_complaint->id;

                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('cc_files', $fileName, 'public'); 
                    $ccFiles->Path = $filePath; 
                    $ccFiles->save();

                    $attachments[] = $filePath;
                }
            }
        }

        Mail::to($department->email)->send(new AssignCcDepartmentMail($customer_complaint, $attachments));
        // if ($request->has('file'))
        // {
            // $ccfile = CcFile::where('customer_complaint_id', $id)->delete();

            // $files = $request->file('file');
            // foreach($files as $file)
            // {
            //     $name = time().'_'.$file->getClientOriginalName();
            //     $file->move(public_path('ccfiles'),$name);
            //     $file_name = '/ccfiles/'.$name;

            //     $ccfile = new CcFile;
            //     $ccfile->customer_complaint_id = $id;
            //     $ccfile->files = $file_name;
            //     $ccfile->filename = $name;
            //     $ccfile->save();
            // }
            // $email = ['richsel.villaruel@wgroup.com.ph', 'bea.bernardino@rico.com.ph'];
            // $concern_department = ConcernDepartment::with('audit')->findOrFail($customer_complaint->Department);
            // $concern_department->notify(new EmailDepartment($concern_department));
        // }

        return response()->json([
            'success' => true,
            'message' => 'Customer satisfaction feedback and files have been successfully assigned.'
        ]);
    }

    public function printCc($id)
    {
        $cc = CustomerComplaint2::with('country', 'product_quality', 'packaging', 'delivery_handling', 'others', 'users', 'noted_by')->findOrFail($id);
        $data = [
            'cc' => $cc,
            'CountryName' => optional($cc->country)->Name,
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('customer_service.cc_pdf', $data);

        return $pdf->stream();
    }

    public function delete($id)
    {
        {
            try {
                $data = ComplaintFile::findOrFail($id);
    
                // Delete file from storage if necessary
                if ($data->Path && file_exists(storage_path('app/public/' . $data->Path))) {
                    unlink(storage_path('app/public/' . $data->Path));
                }
    
                $data->delete();
    
                return response()->json(['success' => 'File deleted successfully.']);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to delete file.'], 500);
            }
        }
    }

    public function delete2($id)
    {
        {
            try {
                $data = CcObjectiveFile::findOrFail($id);
    
                // Delete file from storage if necessary
                if ($data->Path && file_exists(storage_path('app/public/' . $data->Path))) {
                    unlink(storage_path('app/public/' . $data->Path));
                }
    
                $data->delete();
    
                return response()->json(['success' => 'File deleted successfully.']);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to delete file.'], 500);
            }
        }
    }
}
