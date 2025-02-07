<?php

namespace App\Http\Controllers;

use App\Client;
use App\ConcernDepartment;
use App\IssueCategory;
use App\Contact;
use App\CustomerSatisfaction;
use App\CsFiles;
use Illuminate\Support\Facades\Auth;
use App\CustomerRequirement;
use Illuminate\Http\Request;
use App\Mail\CustomerSatisfactionMail;
use App\Mail\AssignDepartmentMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerSatisfactionController extends Controller
{
    public function header()
    {
        return view('customer_service.cs_index');
    }

    public function index()
    {
        $client = Client::all();
        $concern_department = ConcernDepartment::all();
        $category = IssueCategory::all();

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

        return view('customer_service.customer_satisfaction', compact('client', 'concern_department', 'category', 'newCsNo'));
    }

    public function list(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'CsNumber');
        $direction = $request->get('direction', 'asc');
        $fetchAll = filter_var($request->input('fetch_all', false), FILTER_VALIDATE_BOOLEAN);
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
            ->when($progress, function($query) use ($progress, $userId) {
                if ($progress == '20') {
                    $query->where('Progress', '20')
                        ->whereHas('salesapprovers', function ($query) use ($userId) {
                            $query->where('SalesApproverId', $userId);
                        });
                } else {
                    $query->where('Progress', $progress);
                }
            })
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
            ->orderBy($sort, $direction);

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

    public function store(Request $request)
    {
        $customerSatisfaction = CustomerSatisfaction::create([
            'CompanyName' => $request->CompanyName,
            'CsNumber' => $request->CsNumber,
            'ContactName' => $request->ContactName,
            'Concerned' => $request->Concerned,
            'Description' => $request->Description,
            'Category' => $request->Category,
            'ContactNumber' => $request->ContactNumber,
            'Email' => $request->Email,
            // 'Status' => '10',
            'Progress' => '10'
        ]);

        Mail::to($request->Email)
            ->cc('ict.engineer@wgroup.com.ph')
            ->send(new CustomerSatisfactionMail($customerSatisfaction));
       
        // Return success message
        return response()->json(['success' => 'You submitted the form successfully..']);
    }

    public function update(Request $request, $id)
    {
        $data = CustomerSatisfaction::findOrFail($id);
        $data->Response = $request->Response;
        $data->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Customer satisfaction feedback has been successfully updated.'
        ]);
    }

    public function assign(Request $request, $id)
    {
        $data = CustomerSatisfaction::with('concerned')->findOrFail($id);
        $data->Concerned = $request->Concerned;
        $data->save();

        $department = ConcernDepartment::findOrFail($request->Concerned);

        $attachments = [];
        if ($request->hasFile('Path') && is_array($request->file('Path'))) {
            foreach ($request->file('Path') as $file) {
                if ($file->isValid()) {
                    $csFiles = new CsFiles();
                    $csFiles->CsId = $data->id;

                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('cs_files', $fileName, 'public'); 
                    $csFiles->Path = $filePath; 
                    $csFiles->save();
                }
            }
        }

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
        return view('customer_service.cs_view', compact('data', 'concern_department'));
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

    public function noted($id)
    {
        $data = CustomerSatisfaction::findOrFail($id);
        $data->NotedBy = auth()->user()->id;
        $data->Progress = 30;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Customer satisfaction has been successfully updated.'
        ]);
    }

    public function approved($id)
    {
        $data = CustomerSatisfaction::findOrFail($id);
        $data->ApprovedBy = auth()->user()->id;
        $data->Progress = 40;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Customer satisfaction has been successfully approved.'
        ]);
    }

    public function close($id)
    {
        $data = CustomerSatisfaction::findOrFail($id);
        $data->Status = '30';
        $data->Progress = 50;
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
        $cs = CustomerSatisfaction::with('category')->findOrFail($id);
        $data = [
            'cs' => $cs,
            'CategoryName' => optional($cs->category)->Name,
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('customer_service.cs_pdf', $data);

        return $pdf->stream();
    }

}
