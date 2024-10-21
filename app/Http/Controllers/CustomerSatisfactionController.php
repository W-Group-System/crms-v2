<?php

namespace App\Http\Controllers;

use App\Client;
use App\ConcernDepartment;
use App\IssueCategory;
use App\Contact;
use App\CustomerSatisfaction;
use App\CsFiles;
use App\CustomerRequirement;
use Illuminate\Http\Request;
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
            ->when($open && $close, function ($query) use ($open, $close) {
                $query->whereIn('Status', [$open, $close]);
            })
            ->when($open && !$close, function ($query) use ($open) {
                $query->where('Status', $open);
            })
            ->when($close && !$open, function ($query) use ($close) {
                $query->where('Status', $close);
            })
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
            'Status' => '10',
        ]);

        if ($request->hasFile('Path')) {
            foreach ($request->file('Path') as $file) {

                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public'); 

                CsFiles::create([
                    'CsId' => $customerSatisfaction->id, 
                    'Path' => $filePath, 
                ]);
            }
        }
       
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

    public function view($id)
    {
        $data = CustomerSatisfaction::with('concerned', 'category', 'cs_attachments')->findOrFail($id);
        return view('customer_service.cs_view', compact('data'));
    }

    public function received($id)
    {
        $data = CustomerSatisfaction::findOrFail($id);
        $data->ReceivedBy = auth()->user()->id;
        $data->DateReceived = now();
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Customer satisfaction feedback has been successfully received.'
        ]);
    }

    public function close($id)
    {
        $data = CustomerSatisfaction::findOrFail($id);
        $data->Status = '30';
        $data->DateClosed = now();
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Customer satisfaction feedback has been successfully closed.'
        ]);
    }

    public function getContactsByClient($clientId)
    {
        $contacts = Contact::where('CompanyId', $clientId)->pluck('ContactName', 'id');
        return response()->json($contacts);
    }
}
