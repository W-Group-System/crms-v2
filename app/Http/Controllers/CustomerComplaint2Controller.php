<?php

namespace App\Http\Controllers;

use App\CcPackaging;
use App\CcProductQuality;
use App\CcDeliveryHandling;
use App\CcOthers;
use App\Country;
use App\ConcernDepartment;
use App\CustomerComplaint2;
use Illuminate\Http\Request;

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
        $entries = $request->input('number_of_entries', 10);

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
            ]);
        }
    }

    public function store(Request $request)
    {
        $customerComplaint = CustomerComplaint2::create([
            'CompanyName' => $request->CompanyName,
            'CcNumber' => $request->CcNumber,
            'ContactName' => $request->ContactName,
            'Address' => $request->Address,
            'Country' => $request->Country,
            'Telephone' => $request->Telephone,
            'Moc' => $request->Moc,
            'QualityClass' => $request->QualityClass,
            'ProductName' => $request->ProductName,
            'Description' => $request->Description,
            'Currency' => $request->Currency,
            'CustomerRemarks' => $request->CustomerRemarks,
            'SiteConcerned' => $request->SiteConcerned,
            'Department' => $request->Department,
            'Status' => '10',
        ]);

        CcProductQuality::create([
            'CcId' => $customerComplaint->id,
            'Pn1' => $request->Pn1,
            'ScNo1' => $request->ScNo1,
            'SoNo1' => $request->SoNo1,
            'Quantity1' => $request->Quantity1,
            'LotNo1' => $request->LotNo1,
            'Pn2' => $request->Pn2,
            'ScNo2' => $request->ScNo2,
            'SoNo2' => $request->SoNo2,
            'Quantity2' => $request->Quantity2,
            'LotNo2' => $request->LotNo2,
            'Pn3' => $request->Pn3,
            'ScNo3' => $request->ScNo3,
            'SoNo3' => $request->SoNo3,
            'Quantity3' => $request->Quantity3,
            'LotNo3' => $request->LotNo3,
            'Pn4' => $request->Pn4,
            'ScNo4' => $request->ScNo4,
            'SoNo4' => $request->SoNo4,
            'Quantity4' => $request->Quantity4,
            'LotNo4' => $request->LotNo4,
            'Pn5' => $request->Pn5,
            'ScNo5' => $request->ScNo5,
            'SoNo5' => $request->SoNo5,
            'Quantity5' => $request->Quantity5,
            'LotNo5' => $request->LotNo5,
            'Pn6' => $request->Pn6,
            'ScNo6' => $request->ScNo6,
            'SoNo6' => $request->SoNo6,
            'Quantity6' => $request->Quantity6,
            'LotNo6' => $request->LotNo6,
        ]);

        CcPackaging::create([
            'CcId' => $customerComplaint->id,
            'PackPn1' => $request->PackPn1,
            'PackScNo1' => $request->PackScNo1,
            'PackSoNo1' => $request->PackSoNo1,
            'PackQuantity1' => $request->PackQuantity1,
            'PackLotNo1' => $request->PackLotNo1,
            'PackPn2' => $request->PackPn2,
            'PackScNo2' => $request->PackScNo2,
            'PackSoNo2' => $request->PackSoNo2,
            'PackQuantity2' => $request->PackQuantity2,
            'PackLotNo2' => $request->PackLotNo2,
            'PackPn3' => $request->PackPn3,
            'PackScNo3' => $request->PackScNo3,
            'PackSoNo3' => $request->PackSoNo3,
            'PackQuantity3' => $request->PackQuantity3,
            'PackLotNo3' => $request->PackLotNo3,
            'PackPn4' => $request->PackPn4,
            'PackScNo4' => $request->PackScNo4,
            'PackSoNo4' => $request->PackSoNo4,
            'PackQuantity4' => $request->PackQuantity4,
            'PackLotNo4' => $request->PackLotNo4
        ]);

        CcDeliveryHandling::create([
            'CcId' => $customerComplaint->id,
            'DhPn1' => $request->DhPn1,
            'DhScNo1' => $request->DhScNo1,
            'DhSoNo1' => $request->DhSoNo1,
            'DhQuantity1' => $request->DhQuantity1,
            'DhLotNo1' => $request->DhLotNo1,
            'DhPn2' => $request->DhPn2,
            'DhScNo2' => $request->DhScNo2,
            'DhSoNo2' => $request->DhSoNo2,
            'DhQuantity2' => $request->DhQuantity2,
            'DhLotNo2' => $request->DhLotNo2,
            'DhPn3' => $request->DhPn3,
            'DhScNo3' => $request->DhScNo3,
            'DhSoNo3' => $request->DhSoNo3,
            'DhQuantity3' => $request->DhQuantity3,
            'DhLotNo3' => $request->DhLotNo3
        ]);

        CcOthers::create([
            'CcId' => $customerComplaint->id,
            'OthersPn1' => $request->OthersPn1,
            'OthersScNo1' => $request->OthersScNo1,
            'OthersSoNo1' => $request->OthersSoNo1,
            'OthersQuantity1' => $request->OthersQuantity1,
            'OthersLotNo1' => $request->OthersLotNo1,
            'OthersPn2' => $request->OthersPn2,
            'OthersScNo2' => $request->OthersScNo2,
            'OthersSoNo2' => $request->OthersSoNo2,
            'OthersQuantity2' => $request->OthersQuantity2,
            'OthersLotNo2' => $request->OthersLotNo2,
            'OthersPn3' => $request->OthersPn3,
            'OthersScNo3' => $request->OthersScNo3,
            'OthersSoNo3' => $request->OthersSoNo3,
            'OthersQuantity3' => $request->OthersQuantity3,
            'OthersLotNo3' => $request->OthersLotNo3,
            'OthersPn4' => $request->OthersPn4,
            'OthersScNo4' => $request->OthersScNo4,
            'OthersSoNo4' => $request->OthersSoNo4,
            'OthersQuantity4' => $request->OthersQuantity4,
            'OthersLotNo4' => $request->OthersLotNo4
        ]);

        // dd($customerComplaint);
        // return response()->json(['success' => 'You submitted the form successfully..$customerComplaint->id']);
        return response()->json([
            'success' => 'You submitted the form successfully. ID: ' . $request->input('CcNumber')
        ]);
    }

    public function view($id)
    {
        $data = CustomerComplaint2::with('concerned', 'country', 'product_quality', 'packaging', 'delivery_handling', 'others')->findOrFail($id);
        return view('customer_service.cc_view', compact('data'));
    }
}
