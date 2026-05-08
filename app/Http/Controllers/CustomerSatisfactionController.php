<?php

namespace App\Http\Controllers;

use App\Client;
use App\ConcernDepartment;
use App\IssueCategory;
use App\Country;
use App\Contact;
use App\CustomerSatisfaction;
use App\CustomerComplaint2;
use App\CsFiles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Mail\CustomerSatisfactionMail;
use App\Mail\AssignDepartmentMail;
use App\Mail\AcknowledgedMail;
use App\SatisfactionFile;
use App\SatisfactionRemarks;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;


class CustomerSatisfactionController extends Controller
{
    public function header()
    {
        $category = IssueCategory::all();
        $countries = Country::get();

        $year1 = '2' . date('y') ; 

        $latestCs = CustomerSatisfaction::whereYear('created_at', date('Y'))
                        ->orderBy('CsNumber', 'desc')
                        ->first();

        if ($latestCs) {
            $latestSeries = (int) substr($latestCs->CsNumber, -4);
            $newSeries = str_pad($latestSeries + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newSeries = '0001';
        }

        $newCsNo = 'CSR-' . $year1 . '-' . $newSeries;

        $year2 = date('y') . '4'; 
        
        $latestCc = CustomerComplaint2::whereYear('created_at', date('Y'))
                        ->orderBy('CcNumber', 'desc')
                        ->first();
        
        if ($latestCc) {
            $latestSeries = (int) substr($latestCc->CcNumber, -4);
            $newSeries = str_pad($latestSeries + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newSeries = '0001';
        }

        $newCcNo = 'CCF-' . $year2 . '-' . $newSeries;
        

        return view('customer_service.cs_index', compact('category', 'countries', 'newCsNo'));
    }

    public function headerls()
    {
        $category = IssueCategory::all();
        $countries = Country::get();

        $year1 = '2' . date('y') ; 

        $latestCs = CustomerSatisfaction::whereYear('created_at', date('Y'))
                        ->orderBy('CsNumber', 'desc')
                        ->first();

        if ($latestCs) {
            $latestSeries = (int) substr($latestCs->CsNumber, -4);
            $newSeries = str_pad($latestSeries + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newSeries = '0001';
        }

        $newCsNo = 'CSR-' . $year1 . '-' . $newSeries;

        $year2 = date('y') . '4'; 
        
        $latestCc = CustomerComplaint2::whereYear('created_at', date('Y'))
                        ->orderBy('CcNumber', 'desc')
                        ->first();
        
        if ($latestCc) {
            $latestSeries = (int) substr($latestCc->CcNumber, -4);
            $newSeries = str_pad($latestSeries + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newSeries = '0001';
        }

        // $newCcNo = 'CCF-' . $year2 . '-' . $newSeries;
        

        return view('customer_service.cs_ls_index', compact('category', 'countries', 'newCsNo'));
    }

    public function index()
    {
        $client = Client::all();
        $concern_department = ConcernDepartment::all();
        $category = IssueCategory::all();

        $year = '2' . date('y') ; 

        $latestCs = CustomerSatisfaction::whereYear('created_at', date('Y'))
                        ->orderBy('CsNumber', 'desc')
                        ->first();

        if ($latestCs) {
            $latestSeries = (int) substr($latestCs->CsNumber, -4);
            $newSeries = str_pad($latestSeries + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newSeries = '0001';
        }

        $newCsNo = 'CSR-' . $year . '-' . $newSeries;

        return view('customer_service.customer_satisfaction', compact('client', 'concern_department', 'category', 'newCsNo'));
    }

    public function list(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'Progress'); // default sort column
        $direction = $request->get('direction', 'asc');
        $fetchAll = filter_var($request->input('fetch_all', false), FILTER_VALIDATE_BOOLEAN);
        $role = auth()->user()->role;
        $entries = $request->input('number_of_entries', 10);
        $progress = $request->query('progress'); // Get the status from the query parameters
        $status = $request->query('status'); // Get the status from the query parameters

        $userId = Auth::id(); 
        $userByUser = optional(Auth::user())->user_id;

        $year = date('y') . '4';

        $latestCs = CustomerSatisfaction::whereYear('created_at', date('Y'))
                        ->orderBy('CsNumber', 'desc')
                        ->first();

        if ($latestCs) {
            $latestSeries = (int) substr($latestCs->CsNumber, -4);
            $newSeries = str_pad($latestSeries + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newSeries = '0001';
        }

        $newCsNo = 'CSR-' . $year . '-' . $newSeries;

        // Set default for open status if not present in the request
        $open = $request->input('open');
        $close = $request->input('close');

        $customerSatisfaction = CustomerSatisfaction::with(['concerned', 'category'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('CsNumber', 'LIKE', '%' . $search . '%')
                        ->orWhere('created_at', 'LIKE', '%' . $search . '%')
                        ->orWhere('CompanyName', 'LIKE', '%' . $search . '%')
                        ->orWhere('ContactName', 'LIKE', '%' . $search . '%')
                        ->orWhere('Concerned', 'LIKE', '%' . $search . '%')
                        ->orWhere('Category', 'LIKE', '%' . $search . '%')
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
            ->when(optional($role)->type, function($q) use ($role, $request, $search) {
                if ($role->type == "IS") {
                    $q->where('CsNumber', 'LIKE', "%CSR-IS%");
                } elseif ($role->type == "LS") {
                    $q->where('CsNumber', 'LIKE', "%CSR-LS%");
                } elseif ($role->type == "ITD") {

                } else {
                    $q->whereHas('concernedDept', function ($dept) use ($role) {
                        $dept->where('dept_role_group', $role->type);
                    })->whereNotNull('ApprovedBy');
                }
            })
            ->when($progress, function($query) use ($progress, $userId) {
                if ($progress == '20') {
                    $query->where('Progress', '20')
                        ->whereHas('salesapprovers', function ($query) use ($userId) {
                            $query->where('SalesApproverId', $userId);
                        });
                } else {
                    $query->where('Progress', $progress);
                }
            });
            // ->whereHas('clientCompany', function ($query) use ($userId, $userByUser) {
            //     $query->where(function ($query) use ($userId, $userByUser) {
            //         $query->where('PrimaryAccountManagerId', $userId)
            //             ->orWhere('SecondaryAccountManagerId', $userId)
            //             ->orWhere('PrimaryAccountManagerId', $userByUser)
            //             ->orWhere('SecondaryAccountManagerId', $userByUser);
            //     }); 
            // })                                                          
            // ->orWhere(function ($query) use ($userId) {
            //     // Include entries where 'ReceivedBy' is the same as the 'userId' in the 'salesapprovers' table
            //     $query->where('Progress', '20')
            //         ->whereHas('salesapprovers', function ($query) use ($userId) {
            //             $query->where('SalesApproverId', $userId);
            //         });
            // })
        if ($sort === 'Progress') {
            $customerSatisfaction->orderByRaw('CASE WHEN Progress = 10 THEN 0 ELSE 1 END')
                ->orderBy('id', 'desc');
        } else {
            $customerSatisfaction->orderBy($sort, $direction)
                ->orderBy('id', 'desc');
        }

        if ($fetchAll) {
            $data = $customerSatisfaction->get();
            return response()->json($data);
        } else {    
            $data = $customerSatisfaction->paginate($entries);
            return view('customer_service.cs_list', [
                'search' => $search,
                'data' => $data,
                'open' => $open,
                'close' => $close,
                'fetchAll' => $fetchAll,
                'entries' => $entries,
                'newCsNo' => $newCsNo,
                'progress' => $progress
            ]);
        }
    }

    public function uploadTemp(Request $request) 
    {
        if ($request->hasFile('Path')) {
            $files = $request->file('Path'); 

            $uploaded = [];
            foreach ($files as $file) {
                $fileName = uniqid() . '_' . $file->getClientOriginalName();
                $cleanName = str_replace('#', '_', $fileName);
                $path = $file->storeAs('temp', $cleanName, 'public');

                $uploaded[] = [
                    'id'   => $cleanName, // return only fileName to FilePond
                    'path' => $path
                ];
            }

            // Return only first file if FilePond expects one at a time
            return response()->json($uploaded[0]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }


    public function uploadRevert(Request $request) 
    {
        $fileName = $request->getContent();
        $path = 'temp/' . $fileName;

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return response('', 200);
        }
        return response('', 404);
    }


    public function store(Request $request) 
    {
        $year = date('y');

        if ($request->is('new_customer_satisfaction')) {
            $type = 'IS';
        } elseif ($request->is('new_customer_satisfaction_ls')) {
            $type = 'LS';
        } 
        $last = CustomerSatisfaction::where('CsNumber', 'LIKE', "%CSR-$type-%")
            ->orderBy('id', 'desc')
            ->first();

        $next = $last
            ? intval(substr($last->CsNumber, strrpos($last->CsNumber, '-') + 1)) + 1
            : 1;

        $padded = str_pad($next, 4, '0', STR_PAD_LEFT);

        $csNo = "CSR-$type-$year-$padded";
        DB::beginTransaction(); 
        
        try {

             $customerSatisfaction = CustomerSatisfaction::create([
                'CompanyName' => $request->CompanyName,
                'CsNumber'    => $csNo,
                'ContactName' => $request->ContactName,
                'Concerned'   => $request->Concerned,
                'Description' => $request->Description,
                'Category'    => $request->Category,
                'ContactNumber'=> $request->ContactNumber,
                'Email'       => $request->Email,
                'Status'      => '10',
                'Progress'    => '10'
            ]);

            $attachments = [];

            // 2. Attachments (moved from temp → cs_files)
            if ($request->has('Path') && is_array($request->Path)) {
                foreach ($request->Path as $fileName) {
                    $tempPath = 'temp/' . $fileName;
                    if (Storage::disk('public')->exists($tempPath)) {
                        $newPath = 'cs_files/' . $fileName;
                        Storage::disk('public')->move($tempPath, $newPath);

                        SatisfactionFile::create([
                            'CsId' => $customerSatisfaction->id,
                            'Path' => $newPath
                        ]);

                        $attachments[] = $newPath;
                    }
                }
            }
             // 3. Send email
            // Mail::to($customerSatisfaction->Email)
            //     ->send(new CustomerSatisfactionMail($customerSatisfaction, $attachments, false));
            

            $recipients = [];

            if ($request->is('new_customer_satisfaction')) {
                $recipients = [
                    'international.sales@rico.com.ph',
                    // 'audit@rico.com.ph',
                    'ict.engineer@wgroup.com.ph',
                    'bpd@wgroup.com.ph',
                ];
            } elseif ($request->is('new_customer_satisfaction_ls')) {
                $recipients = [
                    'mrdc.sales@rico.com.ph',
                    // 'audit@rico.com.ph',
                    'ict.engineer@wgroup.com.ph',
                    'bpd@wgroup.com.ph',
                ];
            }

            if (!empty($recipients)) {
                Mail::to($recipients)
                    ->send(new CustomerSatisfactionMail($customerSatisfaction, $attachments, true));
                Mail::to([$customerSatisfaction['Email']]) 
                    ->send(new CustomerSatisfactionMail($customerSatisfaction, $attachments, false));
            }
           
            DB::commit();
            return response()->json(['success' => 'Your customer satisfaction has been submitted successfully!']);
        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json([
                'error' => 'Failed to submit satisfaction: ' . $e->getMessage()
            ], 500);
        }
             
    }

    public function submitRemarks(Request $request, $id)
    {
        // $data = CustomerSatisfaction::findOrFail($id);
        $remarks = new SatisfactionRemarks();
        $remarks->CsId = $id;
        $remarks->Remarks = $request->Remarks;
        $remarks->RemarksBy = auth()->user()->id;
        $remarks->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Internal Remarks has been submitted successfully.'
        ]);
    }

    public function assign(Request $request, $id)
    {
        $data = CustomerSatisfaction::with('concerned')->findOrFail($id);
        $data->Department = $request->Department;
        $data->SiteConcerned = $request->SiteConcerned;
        $data->save();

        // $department = ConcernDepartment::findOrFail($request->Concerned);
        $department = ConcernDepartment::where('id', $request->Department)->firstOrFail();
        // dd($department);
        
        $attachments = [];
        if ($request->has('Path') && is_array($request->Path)) {
            foreach ($request->Path as $fileName) {
                $tempPath = 'temp/' . $fileName;
                if (Storage::disk('public')->exists($tempPath)) {
                    $newPath = 'cs_files/' . $fileName;
                    Storage::disk('public')->move($tempPath, $newPath);

                    CsFiles::create([
                        'CsId' => $data->id,
                        'Path' => $newPath
                    ]);

                    $attachments[] = $newPath;
                }
            }
        }

        // if ($request->hasFile('Path') && is_array($request->file('Path'))) {
        //     foreach ($request->file('Path') as $file) {
        //         if ($file->isValid()) {
        //             $csFiles = new CsFiles();
        //             $csFiles->CsId = $data->id;

        //             $fileName = time() . '_' . $file->getClientOriginalName();
        //             $filePath = $file->storeAs('cs_files', $fileName, 'public'); 
        //             $csFiles->Path = $filePath; 
        //             $csFiles->save();

        //             $attachments[] = $filePath;
        //         }
        //     }
        // }

        Mail::to($department->email)->send(new AssignDepartmentMail($data, $attachments));

        return response()->json([
            'success' => true,
            'message' => 'Customer satisfaction feedback and files have been successfully assigned.'
        ]);
    }

    public function view($id)
    {
        $data = CustomerSatisfaction::with('concerned', 'category', 'cs_attachments')->findOrFail($id);
        $concern_department = ConcernDepartment::all();
        $for_remarks = SatisfactionRemarks::with('user')->where('CsId', $data->id)->get();
        
        // dd(auth()->user())
        return view('customer_service.cs_view', compact('data', 'concern_department', 'for_remarks'));
    }

    public function received($id)
    {
        $data = CustomerSatisfaction::findOrFail($id);
        $data->ReceivedBy = auth()->user()->id;
        $data->DateReceived = now();
        $data->Progress = 20;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Customer satisfaction feedback has been successfully received.'
        ]);
    }

    public function noted($id, Request $request)
    {
        $data = CustomerSatisfaction::findOrFail($id);
        $data->NotedBy = auth()->user()->id;
        $data->Progress = 30;
        $data->NotedRemarks = $request->NotedRemarks;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Customer satisfaction has been successfully updated.'
        ]);
    }

    public function approved(Request $request, $id)
    {
        $data = CustomerSatisfaction::findOrFail($id);
        $data->ApprovedBy = auth()->user()->id;
        $data->ApprovedDate = now();
        $data->Progress = 40;
        $data->save();

        $attachments = [];
        
        // Mail::to(['audit@rico.com.ph', 'bpd@wgroup.com.ph'])
        Mail::to(['ict.engineer@wgroup.com.ph', 'bpd@wgroup.com.ph'])
            ->send(new AcknowledgedMail($data, $attachments));

        return response()->json([
            'success' => true,
            'message' => 'Customer satisfaction has been successfully acknowledged.'
        ]);
    }

    public function close($id)
    {
        $data = CustomerSatisfaction::findOrFail($id);
        $data->Status = '30';
        // $data->Progress = 50;
        $data->DateClosed = now();
        $data->ClosedBy = auth()->user()->id;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Customer satisfaction has been successfully closed.'
        ]);
    }

    public function getContactsByClient($clientId)
    {
        $contacts = Contact::where('CompanyId', $clientId)->pluck('ContactName', 'id');
        return response()->json($contacts);
    }

    public function printCs($id)
    {
        $cs = CustomerSatisfaction::with('category', 'remarks')->findOrFail($id);
        $data = [
            'cs' => $cs,
            'CategoryName' => optional($cs->category)->Name,
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('customer_service.cs_pdf', $data);

        return $pdf->stream();
    }

    // Delete
    public function delete($id)
    {
        try {
            $data = CsFiles::findOrFail($id);

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
