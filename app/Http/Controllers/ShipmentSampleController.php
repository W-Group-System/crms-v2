<?php

namespace App\Http\Controllers;

use App\ShipmentSample;
use App\SseFiles;
use App\SsePacks;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ShipmentSampleController extends Controller
{
    // List 
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'SseNumber'); // Default to 'Name' if no sort is specified
        $direction = $request->get('direction', 'asc'); // Default to ascending order
        $fetchAll = $request->input('fetch_all', false); // Get the fetch_all parameter
        $entries = $request->input('number_of_entries', 10); // Default to 10 entries per page
        $refCode = $this->refCode();

        $year = date('y'); // For example, '24' for 2024

        // Fetch the latest SpeNo from the database and extract the series part
        $latestSse = ShipmentSample::whereYear('created_at', date('Y'))
                        ->orderBy('SseNumber', 'desc')
                        ->first();

        if ($latestSse) {
            // Extract and increment the numeric part of the latest series (e.g., 0001 -> 0002)
            $latestSeries = (int) substr($latestSse->SseNumber, -4);
            $newSeries = str_pad($latestSeries + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // If no previous records exist, start with '0001'
            $newSeries = '0001';
        }

        // Combine to create the new SpeNo (e.g., SPE-24-0001)
        $newSseNo = 'SSE-' . $year . '-' . $newSeries;

        $shipmentSample = ShipmentSample::where(function ($query) use ($search) {
                $query->where('SseNumber', 'LIKE', '%' . $search . '%')  
                    ->orWhere('AttentionTo', 'LIKE', '%' . $search . '%')
                    ->orWhere('RmType', 'LIKE', '%' . $search . '%')
                    ->orWhere('SseResult', 'LIKE', '%' . $search . '%');
            })
            ->orderBy($sort, $direction);
        if ($fetchAll) {
            $data = $shipmentSample->get(); // Fetch all results
            return response()->json($data); // Return JSON response for copying
        } else {
            $data = $shipmentSample->paginate($entries); // Default pagination
            // dd($data);
            return view('sse.index', [
                'search' => $search,
                'data' => $data,
                'fetchAll' => $fetchAll,
                'entries' => $entries,
                'refCode' => $refCode, 
                'newSseNo' => $newSseNo
            ]);
        }

    }


    // Store 
    public function store(Request $request) 
    {
        $rules = [
            'RmType' => 'required|string|max:255',
            'Supplier' => 'required|string|max:255',
            'SseResult' => 'required|string|max:255',
            'AttentionTo' => 'nullable|string|max:255',
            'ProductCode' => 'nullable|string|max:255',
            'Grade' => 'nullable|string|max:255',
            'Origin' => 'nullable|string|max:255',  
        ];

        $customMessages = [
            'RmType.required' => 'The raw material type is required.',
            'SseResult.required' => 'The result is required.',
            'Supplier.required' => 'The supplier is required.',           
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $shipment_sample = new ShipmentSample();
        $shipment_sample->SseNumber = $request->SseNumber;
        $shipment_sample->DateSubmitted = $request->DateSubmitted;
        $shipment_sample->AttentionTo = $request->AttentionTo;
        $shipment_sample->RmType = $request->RmType;
        $shipment_sample->Grade = $request->Grade;
        $shipment_sample->ProductCode = $request->ProductCode;
        $shipment_sample->Origin = $request->Origin;
        $shipment_sample->Supplier = $request->Supplier;
        $shipment_sample->SseResult = $request->SseResult;
        $shipment_sample->Origin = $request->Origin;
        $shipment_sample->ResultSpeNo = $request->ResultSpeNo;
        $shipment_sample->PoNumber = $request->PoNumber;
        $shipment_sample->Quantity = $request->Quantity;
        $shipment_sample->ProductOrdered = $request->ProductOrdered;
        $shipment_sample->Ordered = $request->Ordered;
        $shipment_sample->SampleType = $request->SampleType;
        $shipment_sample->Status = 10;
        $shipment_sample->Buyer = $request->Buyer;
        $shipment_sample->BuyersPo = $request->BuyersPo;
        $shipment_sample->SalesAgreement = $request->SalesAgreement;
        $shipment_sample->ProductDeclared = $request->ProductDeclared;
        $shipment_sample->LnBags = $request->LnBags;
        $shipment_sample->Instruction = $request->Instruction;
        $shipment_sample->save();

        if ($request->has('LotNumber') && is_array($request->LotNumber)) {
            SsePacks::where('SseId', $shipment_sample->id)->delete();
        
            foreach ($request->LotNumber as $index => $shipment_lotnumber) {
                if (!empty($shipment_lotnumber) && !empty($request->QtyRepresented[$index])) {
                    $shipmentSample = new SsePacks();
                    $shipmentSample->SseId = $shipment_sample->id;
                    $shipmentSample->LotNumber = $shipment_lotnumber;
                    $shipmentSample->QtyRepresented = $request->QtyRepresented[$index]; 
                    $shipmentSample->save();
                }
            }
        } 

        if ($request->has('Name') && is_array($request->Name)) {
            foreach ($request->Name as $index => $name) {
                // Ensure the file exists before saving
                if ($request->hasFile('Path.' . $index)) {
                    $shipmentFiles = new SseFiles();
                    $shipmentFiles->SseId = $shipment_sample->id;
                    $shipmentFiles->Name = $name; // Save the selected attachment name

                    // Handle file upload
                    $file = $request->file('Path.' . $index);
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('uploads', $fileName, 'public'); // Store file in 'public' disk
                    $shipmentFiles->Path = $filePath; // Save the file path

                    $shipmentFiles->save();
                }
            }
        }

        return response()->json(['success' => 'Shipment Sample added successfully!']);
    }

    public function edit($id)
    {
        if(request()->ajax()) {
            $data = ShipmentSample::with('shipment_pack', 'shipment_attachments')->findOrFail($id);
            
            $shipment_attachments = $data->shipment_attachments->map(function($shipment_attachments) {
                return [
                    'id' => $shipment_attachments->id, 
                    'name' => $shipment_attachments->Name,
                    'path' => $shipment_attachments->Path,
                ];
            });

            return response()->json([
                'data' => $data,
                'LotNumber' => $data->shipment_attachments->pluck('LotNumber')->toArray(), 
                'shipment_attachments' => $shipment_attachments,
            ]);
        }
    }

    public function view($id)
    {
        $data = ShipmentSample::with('shipment_pack', 'shipment_attachments')->findOrFail($id);
        return view('sse.view', compact('data'));
    }

    public function refCode()
    {
        return array(
            'RND' => 'RND',
            'QCD-WHI' => 'QCD-WHI',
            'QCD-PBI' => 'QCD-PBI',
            'QCD-MRDC' => 'QCD-MRDC',
            'QCD-CCC' => 'QCD-CCC'
        );
    }
}
