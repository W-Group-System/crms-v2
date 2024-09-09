<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Client;
use App\CustomerRequirement;
use App\PaymentTerms;
use App\PriceMonitoring;
use App\PriceRequestProduct;
use App\RequestProductEvaluation;
use App\SampleRequest;
use App\User;
use App\Product;
use App\SrfProgress;
use League\Csv\Writer;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionActivityExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    // Price Request
    public function price_summary(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'DateRequested');
        $direction = $request->get('direction', 'desc');
        $fetchAll = $request->input('fetch_all', false);
        $entries = $request->input('number_of_entries', 10);

        // Use provided 'from' and 'to' dates or default to the current month if not provided
        $from = $request->input('from') ?: now()->startOfMonth()->format('Y-m-d');
        $to = $request->input('to') ?: now()->endOfMonth()->format('Y-m-d');

        $validSorts = ['DateRequested', 'PrimarySalesPersonId', 'ClientId', 'ProductCode', 'ProductRmc', 'OfferedPrice', 'ShipmentTerm', 'PaymentTerm', 'QuantityRequired', 'Margin', 'MarginPercentage', 'TotalMargin', 'IsAccepted', 'Remarks'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'DateRequested'; // Default sort field
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        // Optimize base query with necessary joins only
        $query = PriceMonitoring::query()
            ->with([
                'primarySalesPerson:user_id,full_name', 
                'client:id,name', 
                'priceRequestProduct:id,code', 
                'priceRequestProduct:id,ProductRmc,IsalesOfferedPrice,QuantityRequired,IsalesMargin,IsalesMarginPercentage', 
                'paymentterms:id,Name'
            ])
            ->where(function ($query) use ($search) {
                $query->where('DateRequested', 'LIKE', '%' . $search . '%')
                    ->orWhere('ShipmentTerm', 'LIKE', '%' . $search . '%')
                    ->orWhere('code', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('primarySalesPerson', function ($q) use ($search) {
                        $q->where('full_name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('priceRequestProduct', function ($q) use ($search) {
                        $q->where('ProductRmc', 'LIKE', '%' . $search . '%')
                        ->orWhere('IsalesOfferedPrice', 'LIKE', '%' . $search . '%')
                        ->orWhere('QuantityRequired', 'LIKE', '%' . $search . '%')
                        ->orWhere('IsalesMargin', 'LIKE', '%' . $search . '%')
                        ->orWhere('IsalesMarginPercentage', 'LIKE', '%' . $search . '%')
                        ->orWhere('Remarks', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('paymentterms', function ($q) use ($search) {
                        $q->where('Name', 'LIKE', '%' . $search . '%');
                    });
            })
            ->whereBetween('pricerequestforms.DateRequested', [$from, $to])
            ->leftJoin('pricerequestproducts', 'pricerequestproducts.id', '=', 'pricerequestforms.id')
            ->leftJoin('products', 'products.id', '=', 'pricerequestproducts.ProductId')
            ->when($sort, function ($query) use ($sort, $direction) {
                if ($sort == 'PrimarySalesPersonId') {
                    $query->orderBy('PrimarySalesPersonId', $direction);
                } elseif ($sort == 'ClientId') {
                    $query->orderBy('ClientId', $direction);
                } elseif ($sort == 'ProductCode') {
                    $query->orderBy('products.code', $direction);
                } elseif ($sort == 'PaymentTermId') {
                    $query->orderBy('PaymentTermId', $direction);
                } else {
                    $query->orderBy($sort, $direction);
                }
            });
        
        $priceRequests = $query->get();   
        // dd($priceRequests->take(1));
        // Apply Filters
        if ($request->filled('filter_date')) {
            $query->whereDate('DateRequested', $request->filter_date);
        }
        
        if ($request->filled('filter_account_manager')) {
            $query->where('PrimarySalesPersonId', $request->filter_account_manager);
        }
        
        if ($request->filled('filter_client')) {
            $query->where('ClientId', $request->filter_client);
        }        

        if ($fetchAll) {
            return response()->json($query->get());
        } else {
            $priceRequests = $query->paginate($entries);        

            // Cache or reuse these results if possible
            $allIds = PriceMonitoring::pluck('id')->unique();

            // Use these optimized queries
            $allDates = PriceMonitoring::whereIn('pricerequestforms.id', $allIds)->pluck('DateRequested')->unique()->sort()->values();
            $allPrf = PriceMonitoring::whereIn('pricerequestforms.id', $allIds)->pluck('PrfNumber')->unique()->sort()->values();
            $allPrimarySalesPersons = User::whereIn('users.user_id', PriceMonitoring::pluck('PrimarySalesPersonId')->unique())
                ->pluck('full_name', 'users.user_id')
                ->sort();
            $allClients = Client::whereIn('id', PriceMonitoring::pluck('ClientId')->unique())
                ->pluck('Name', 'id')
                ->sort();
            $allProducts = PriceRequestProduct::whereIn('pricerequestproducts.PriceRequestFormId', $allIds)
                ->join('products', 'pricerequestproducts.ProductId', '=', 'products.id') 
                ->distinct()
                ->pluck('products.code'); 
            $allProductRmc = PriceRequestProduct::whereIn('pricerequestproducts.PriceRequestFormId', $allIds)
                ->distinct()
                ->pluck('ProductRmc')
                ->sort()
                ->values();
            $allOfferedPrice = PriceRequestProduct::whereIn('pricerequestproducts.PriceRequestFormId', $allIds)
                ->distinct()
                ->pluck('IsalesOfferedPrice')
                ->sort()
                ->values();
            $allMargin = PriceRequestProduct::whereIn('pricerequestproducts.PriceRequestFormId', $allIds)
                ->pluck('IsalesMargin')
                ->sort()
                ->values();
            $allPercentMargin = PriceRequestProduct::whereIn('pricerequestproducts.PriceRequestFormId', $allIds)
                ->pluck('IsalesMarginPercentage')
                ->sort()
                ->values();
            $totalMargins = PriceRequestProduct::all()->map(function($priceRequest) {
                return number_format($priceRequest->QuantityRequired * $priceRequest->IsalesMargin, 2);
            })->unique()->sort();
            $allShipments = PriceMonitoring::whereIn('pricerequestforms.id', $allIds)->pluck('ShipmentTerm')->unique()->sort()->values();
            $allPayments = PaymentTerms::whereIn('clientpaymentterms.id', PriceMonitoring::pluck('PaymentTermId')->unique())
                ->pluck('Name', 'clientpaymentterms.id')
                ->sort();
            $allQuantity = PriceRequestProduct::whereIn('pricerequestproducts.PriceRequestFormId', $allIds)
                ->distinct()
                ->pluck('QuantityRequired')
                ->sort()
                ->values();
            $allAccepted = PriceMonitoring::whereIn('pricerequestforms.id', $allIds)->pluck('IsAccepted')->unique()->sort()->values();
            $allRemarks = PriceMonitoring::whereIn('pricerequestforms.id', $allIds)->pluck('Remarks')->unique()->sort()->values();
            
            return view('reports.price_summary', compact(
                'search', 'priceRequests', 'entries', 'fetchAll', 'sort', 'direction',
                'allPrimarySalesPersons', 'allProductRmc', 'allDates', 'allClients','allShipments', 'allPayments', 'allQuantity', 'allAccepted', 'allRemarks', 'allProducts', 'allOfferedPrice', 'allMargin', 'allPercentMargin', 'totalMargins', 'allPrf', 'from', 'to'
            ));
        }
    }

    // Export Price Requests
    // public function exportPriceRequest(Request $request)
    // {
    //     $search = $request->input('search');
    //     $sort = $request->get('sort', 'DateRequested'); // Default to 'DateRequested' if no sort is specified
    //     $direction = $request->get('direction', 'asc'); // Default to ascending order
    //     $from = $request->input('from') ?: now()->startOfMonth()->format('Y-m-d');
    //     $to = $request->input('to') ?: now()->endOfMonth()->format('Y-m-d');

    //     $validSorts = ['DateRequested', 'PrimarySalesPersonId', 'ClientId', 'ProductCode', 'ProductRmc', 'OfferedPrice', 'ShipmentTerm', 'PaymentTerm', 'QuantityRequired', 'Margin', 'MarginPercentage', 'TotalMargin', 'IsAccepted', 'Remarks'];
    //     if (!in_array($sort, $validSorts)) {
    //         $sort = 'DateRequested'; // Default sort field
    //     }
    //     if (!in_array($direction, ['asc', 'desc'])) {
    //         $direction = 'desc';
    //     }

    //     // Fetch all records based on search, sort, and direction
    //     $priceRequests = PriceMonitoring::with([
    //             'primarySalesPerson:user_id,full_name', 
    //             'client:id,name', 
    //             'products',
    //             'paymentterms:id,Name'
    //         ])// Eager load relationships
    //         ->leftJoin('pricerequestproducts', 'pricerequestproducts.id', '=', 'pricerequestforms.id')
    //         ->where(function ($query) use ($search) {
    //             $query->where('DateRequested', 'LIKE', '%' . $search . '%')
    //                 ->orWhere('ShipmentTerm', 'LIKE', '%' . $search . '%')
    //                 ->orWhere('code', 'LIKE', '%' . $search . '%')
    //                 ->orWhereHas('primarySalesPerson', function ($q) use ($search) {
    //                     $q->where('full_name', 'LIKE', '%' . $search . '%');
    //                 })
    //                 ->orWhereHas('client', function ($q) use ($search) {
    //                     $q->where('name', 'LIKE', '%' . $search . '%');
    //                 })
    //                 ->orWhereHas('priceRequestProduct', function ($q) use ($search) {
    //                     $q->where('ProductRmc', 'LIKE', '%' . $search . '%')
    //                     ->orWhere('IsalesOfferedPrice', 'LIKE', '%' . $search . '%')
    //                     ->orWhere('QuantityRequired', 'LIKE', '%' . $search . '%')
    //                     ->orWhere('IsalesMargin', 'LIKE', '%' . $search . '%')
    //                     ->orWhere('IsalesMarginPercentage', 'LIKE', '%' . $search . '%')
    //                     ->orWhere('Remarks', 'LIKE', '%' . $search . '%');
    //                 })
    //                 ->orWhereHas('paymentterms', function ($q) use ($search) {
    //                     $q->where('Name', 'LIKE', '%' . $search . '%');
    //                 });
    //         })
    //         ->whereBetween('pricerequestforms.DateRequested', [$from, $to])
    //         ->leftJoin('pricerequestproducts', 'pricerequestproducts.id', '=', 'pricerequestforms.id')
    //         ->leftJoin('products', 'products.id', '=', 'pricerequestproducts.ProductId')
    //         ->orderBy($sort, $direction);
            
     
    //     // Convert data to an array format that can be easily handled by JavaScript
    //     return response()->json($priceRequests);
    // }
    public function exportPriceRequest(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'DateRequested'); // Default to 'DateRequested' if no sort is specified
        $direction = $request->get('direction', 'desc'); // Default to 'desc' if no direction is specified
        $from = $request->input('from') ? Carbon::parse($request->input('from'))->format('Y-m-d') : now()->startOfMonth()->format('Y-m-d');
        $to = $request->input('to') ? Carbon::parse($request->input('to'))->format('Y-m-d') : now()->endOfMonth()->format('Y-m-d');

        $validSorts = ['DateRequested', 'PrimarySalesPersonId', 'ClientId', 'ProductCode', 'ProductRmc', 'OfferedPrice', 'ShipmentTerm', 'PaymentTerm', 'QuantityRequired', 'Margin', 'MarginPercentage', 'TotalMargin', 'IsAccepted', 'Remarks'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'DateRequested'; // Default sort field
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        // Fetch all records based on search, sort, and direction
        $priceRequests = PriceMonitoring::query()
            ->with([
                'primarySalesPerson:user_id,full_name', 
                'client:id,name', 
                'priceRequestProduct:ProductId,id',
                'priceRequestProduct:id,ProductRmc,IsalesOfferedPrice,QuantityRequired,IsalesMargin,IsalesMarginPercentage', 
                'paymentterms:id,Name'
            ])
            ->where(function ($query) use ($search) {
                $query->where('DateRequested', 'LIKE', '%' . $search . '%')
                    ->orWhere('ShipmentTerm', 'LIKE', '%' . $search . '%')
                    ->orWhere('code', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('primarySalesPerson', function ($q) use ($search) {
                        $q->where('full_name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('priceRequestProduct', function ($q) use ($search) {
                        $q->where('ProductRmc', 'LIKE', '%' . $search . '%')
                        ->orWhere('IsalesOfferedPrice', 'LIKE', '%' . $search . '%')
                        ->orWhere('QuantityRequired', 'LIKE', '%' . $search . '%')
                        ->orWhere('IsalesMargin', 'LIKE', '%' . $search . '%')
                        ->orWhere('IsalesMarginPercentage', 'LIKE', '%' . $search . '%')
                        ->orWhere('Remarks', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('paymentterms', function ($q) use ($search) {
                        $q->where('Name', 'LIKE', '%' . $search . '%');
                    });
            })
            ->whereBetween('pricerequestforms.DateRequested', [$from, $to])
            ->leftJoin('pricerequestproducts', 'pricerequestproducts.id', '=', 'pricerequestforms.id')
            ->leftJoin('products', 'products.id', '=', 'pricerequestproducts.ProductId')
            ->orderBy($sort, $direction)
            ->get(); // Execute the query and get the results
        // dd($priceRequests);
        // Convert data to an array format that can be easily handled by JavaScript
        $data = $priceRequests->map(function ($item) {
            return [
                'DateRequested' => $item->DateRequested,
                'PrimarySalesPersonId' => $item->primarySalesPerson->full_name ?? 'N/A',
                'Client' => $item->client->name ?? 'N/A',
                'ProductCode' => $item->code ?? 'N/A',
                'ProductRmc' => $item->ProductRmc ?? 'N/A',
                'OfferedPrice' => $item->IsalesOfferedPrice ?? 'N/A',
                'QuantityRequired' => $item->QuantityRequired ?? 'N/A',
                'Margin' => $item->IsalesMargin ?? 'N/A',
                'MarginPercentage' => $item->IsalesMarginPercentage ?? 'N/A',
                'TotalMargin' => $item->TotalMargin ?? 'N/A',
                'ShipmentTerm' => $item->ShipmentTerm ?? 'N/A',
                'PaymentTerm' => $item->paymentterms->Name ?? 'N/A',
                'IsAccepted' => $item->IsAccepted ? 'YES' : 'NO',
                'Remarks' => $item->Remarks ?? 'N/A',
            ];
        });
        
        // Return the data as JSON
        return response()->json($data);
    }

    // Transaction/Activity
    public function transaction_summary(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'client'); 
        $direction = $request->get('direction', 'desc');
        $fetchAll = $request->input('fetch_all', false);
        $entries = $request->input('number_of_entries', 10);

        // Use provided 'from' and 'to' dates or default to the current month if not provided
        $from = $request->input('from') ?: now()->startOfMonth()->format('Y-m-d');
        $to = $request->input('to') ?: now()->endOfMonth()->format('Y-m-d');

        // Get filter inputs
        $filterType = $request->input('filter_type');
        $filterTransactionNumber = $request->input('filter_transaction_number');
        $filterBDE = $request->input('filter_bde');
        $filterClient = $request->input('filter_client');
        $filterDateCreated = $request->input('filter_date_created');
        $filterDueDate = $request->input('filter_due_date');
        $filterStatus = $request->input('filter_status');
        $filterProgress = $request->input('filter_progress');

        // Ensure sort and direction are valid
        $validSorts = ['status', 'client', 'transaction_number', 'date_created'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'client'; // Default sort field
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        // Define subqueries
        $crr_data = CustomerRequirement::select(
                        'customerrequirements.id', 
                        'customerrequirements.CrrNumber as transaction_number', 
                        'users.full_name as bde',
                        'clientcompanies.Name as client', 
                        'customerrequirements.DateCreated as date_created', 
                        'customerrequirements.DueDate as due_date', 
                        'customerrequirements.DetailsOfRequirement as details', 
                        'customerrequirements.Recommendation as result', 
                        'customerrequirements.Status as status', 
                        'srfprogresses.name as progress', 
                        DB::raw("'Customer Requirement' as type")
                    )
                    ->leftJoin('users', 'customerrequirements.PrimarySalesPersonId', '=', 'users.user_id')
                    ->leftJoin('clientcompanies', 'customerrequirements.ClientId', '=', 'clientcompanies.id')
                    ->leftJoin('srfprogresses', 'customerrequirements.Progress', '=', 'srfprogresses.id')
                    ->where(function ($query) use ($search) {
                        $query->where('customerrequirements.CrrNumber', 'LIKE', '%' . $search . '%')
                            ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                            ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                            ->orWhere('customerrequirements.DetailsOfRequirement', 'LIKE', '%' . $search . '%')
                            ->orWhere('customerrequirements.Recommendation', 'LIKE', '%' . $search . '%')
                            ->orWhere('customerrequirements.Status', 'LIKE', '%' . $search . '%')
                            ->orWhere('srfprogresses.name', 'LIKE', '%' . $search . '%')
                            ->orWhere(DB::raw("'Customer Requirement'"), 'LIKE', '%' . $search . '%');
                    })
                    ->whereBetween('customerrequirements.DateCreated', [$from, $to])
                    ->when($filterType, function ($query) use ($filterType) {
                        return $query->where(DB::raw("'Customer Requirement'"), '=', $filterType);
                    })
                    ->when($filterTransactionNumber, function ($query) use ($filterTransactionNumber) {
                        return $query->where('customerrequirements.CrrNumber', 'LIKE', '%' . $filterTransactionNumber . '%');
                    })
                    ->when($filterBDE, function ($query) use ($filterBDE) {
                        return $query->where('users.full_name', 'LIKE', '%' . $filterBDE . '%');
                    })
                    ->when($filterClient, function ($query) use ($filterClient) {
                        return $query->where('clientcompanies.Name', 'LIKE', '%' . $filterClient . '%');
                    })
                    ->when($filterDateCreated, function ($query) use ($filterDateCreated) {
                        return $query->where('customerrequirements.DateCreated', 'LIKE', '%' . $filterDateCreated . '%');
                    })
                    ->when($filterDueDate, function ($query) use ($filterDueDate) {
                        return $query->where('customerrequirements.DueDate', 'LIKE', '%' . $filterDueDate . '%');
                    })
                    ->when($filterStatus, function ($query) use ($filterStatus) {
                        return $query->where('customerrequirements.Status', 'LIKE', '%' . $filterStatus . '%');
                    })
                    ->when($filterProgress, function ($query) use ($filterProgress) {
                        return $query->where('srfprogresses.name', 'LIKE', '%' . $filterProgress . '%');
                    });

        $srf_data = SampleRequest::select(
                        'samplerequests.Id', 
                        'samplerequests.SrfNumber as transaction_number', 
                        'users.full_name as bde',
                        'clientcompanies.Name as client', 
                        'samplerequests.created_at as date_created', 
                        'samplerequests.DateRequired as due_date', 
                        'samplerequests.InternalRemarks as details', 
                        'samplerequests.Disposition as result',
                        'samplerequests.Status as status', 
                        'srfprogresses.name as progress', 
                        DB::raw("'Sample Request' as type")
                    )
                    ->leftJoin('users', 'samplerequests.PrimarySalesPersonId', '=', 'users.user_id')
                    ->leftJoin('clientcompanies', 'samplerequests.ClientId', '=', 'clientcompanies.id')
                    ->leftJoin('srfprogresses', 'samplerequests.Progress', '=', 'srfprogresses.id')
                    ->where(function ($query) use ($search) {
                        $query->where('samplerequests.SrfNumber', 'LIKE', '%' . $search . '%')
                            ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                            ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                            ->orWhere('samplerequests.InternalRemarks', 'LIKE', '%' . $search . '%')
                            ->orWhere('samplerequests.Disposition', 'LIKE', '%' . $search . '%')
                            ->orWhere('samplerequests.Status', 'LIKE', '%' . $search . '%')
                            ->orWhere('srfprogresses.name', 'LIKE', '%' . $search . '%')
                            ->orWhere(DB::raw("'Sample Request'"), 'LIKE', '%' . $search . '%');
                    })
                    ->whereBetween('samplerequests.created_at', [$from, $to])
                    ->when($filterType, function ($query) use ($filterType) {
                        return $query->where(DB::raw("'Sample Request'"), '=', $filterType);
                    })
                    ->when($filterTransactionNumber, function ($query) use ($filterTransactionNumber) {
                        return $query->where('samplerequests.SrfNumber', 'LIKE', '%' . $filterTransactionNumber . '%');
                    })
                    ->when($filterBDE, function ($query) use ($filterBDE) {
                        return $query->where('users.full_name', 'LIKE', '%' . $filterBDE . '%');
                    })
                    ->when($filterClient, function ($query) use ($filterClient) {
                        return $query->where('clientcompanies.Name', 'LIKE', '%' . $filterClient . '%');
                    })
                    ->when($filterDateCreated, function ($query) use ($filterDateCreated) {
                        return $query->where('samplerequests.created_at', 'LIKE', '%' . $filterDateCreated . '%');
                    })
                    ->when($filterDueDate, function ($query) use ($filterDueDate) {
                        return $query->where('samplerequests.DateRequired', 'LIKE', '%' . $filterDueDate . '%');
                    })
                    ->when($filterStatus, function ($query) use ($filterStatus) {
                        return $query->where('samplerequests.Status', 'LIKE', '%' . $filterStatus . '%');
                    })
                    ->when($filterProgress, function ($query) use ($filterProgress) {
                        return $query->where('srfprogresses.name', 'LIKE', '%' . $filterProgress . '%');
                    });

        $rpe_data = RequestProductEvaluation::select(
                        'requestproductevaluations.id', 
                        'requestproductevaluations.RpeNumber as transaction_number', 
                        'users.full_name as bde',
                        'clientcompanies.Name as client', 
                        'requestproductevaluations.CreatedDate as date_created', 
                        'requestproductevaluations.DueDate as due_date', 
                        'requestproductevaluations.ObjectiveForRpeProject as details', 
                        'requestproductevaluations.RpeResult as result',
                        'requestproductevaluations.Status as status', 
                        'srfprogresses.name as progress', 
                        DB::raw("'Request Product Evaluation' as type")
                    )
                    ->leftJoin('users', 'requestproductevaluations.PrimarySalesPersonId', '=', 'users.user_id')
                    ->leftJoin('clientcompanies', 'requestproductevaluations.ClientId', '=', 'clientcompanies.id')
                    ->leftJoin('srfprogresses', 'requestproductevaluations.Progress', '=', 'srfprogresses.id')
                    ->where(function ($query) use ($search) {
                        $query->where('requestproductevaluations.RpeNumber', 'LIKE', '%' . $search . '%')
                            ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                            ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                            ->orWhere('requestproductevaluations.ObjectiveForRpeProject', 'LIKE', '%' . $search . '%')
                            ->orWhere('requestproductevaluations.RpeResult', 'LIKE', '%' . $search . '%')
                            ->orWhere('requestproductevaluations.Status', 'LIKE', '%' . $search . '%')
                            ->orWhere('srfprogresses.name', 'LIKE', '%' . $search . '%')
                            ->orWhere(DB::raw("'Request Product Evaluation'"), 'LIKE', '%' . $search . '%');
                    })
                    ->whereBetween('requestproductevaluations.CreatedDate', [$from, $to])
                    ->when($filterType, function ($query) use ($filterType) {
                        return $query->where(DB::raw("'Request Product Evaluation'"), '=', $filterType);
                    })
                    ->when($filterTransactionNumber, function ($query) use ($filterTransactionNumber) {
                        return $query->where('requestproductevaluations.RpeNumber', 'LIKE', '%' . $filterTransactionNumber . '%');
                    })
                    ->when($filterBDE, function ($query) use ($filterBDE) {
                        return $query->where('users.full_name', 'LIKE', '%' . $filterBDE . '%');
                    })
                    ->when($filterClient, function ($query) use ($filterClient) {
                        return $query->where('clientcompanies.Name', 'LIKE', '%' . $filterClient . '%');
                    })
                    ->when($filterDateCreated, function ($query) use ($filterDateCreated) {
                        return $query->where('requestproductevaluations.CreatedDate', 'LIKE', '%' . $filterDateCreated . '%');
                    })
                    ->when($filterDueDate, function ($query) use ($filterDueDate) {
                        return $query->where('requestproductevaluations.DueDate', 'LIKE', '%' . $filterDueDate . '%');
                    })
                    ->when($filterStatus, function ($query) use ($filterStatus) {
                        return $query->where('requestproductevaluations.Status', 'LIKE', '%' . $filterStatus . '%');
                    })
                    ->when($filterProgress, function ($query) use ($filterProgress) {
                        return $query->where('srfprogresses.name', 'LIKE', '%' . $filterProgress . '%');
                    });

        $price_data = PriceMonitoring::select(
                        'pricerequestforms.id', 
                        'pricerequestforms.PrfNumber as transaction_number', 
                        'users.full_name as bde',
                        'clientcompanies.Name as client', 
                        'pricerequestforms.created_at as date_created', 
                        'pricerequestforms.ValidityDate as due_date', 
                        'pricerequestforms.Remarks as details', 
                        'pricerequestforms.DispositionRemarks as result',
                        'pricerequestforms.Status as status', 
                        'srfprogresses.name as progress', 
                        DB::raw("'Price Request' as type")
                    )
                    ->leftJoin('users', 'pricerequestforms.PrimarySalesPersonId', '=', 'users.user_id')
                    ->leftJoin('clientcompanies', 'pricerequestforms.ClientId', '=', 'clientcompanies.id')
                    ->leftJoin('srfprogresses', 'pricerequestforms.Progress', '=', 'srfprogresses.id')
                    ->where(function ($query) use ($search) {
                        $query->where('pricerequestforms.PrfNumber', 'LIKE', '%' . $search . '%')
                            ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                            ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                            ->orWhere('pricerequestforms.Remarks', 'LIKE', '%' . $search . '%')
                            ->orWhere('pricerequestforms.DispositionRemarks', 'LIKE', '%' . $search . '%')
                            ->orWhere('pricerequestforms.Status', 'LIKE', '%' . $search . '%')
                            ->orWhere('srfprogresses.name', 'LIKE', '%' . $search . '%')
                            ->orWhere(DB::raw("'Price Request'"), 'LIKE', '%' . $search . '%');
                    })
                    ->whereBetween('pricerequestforms.created_at', [$from, $to])
                    ->when($filterType, function ($query) use ($filterType) {
                        return $query->where(DB::raw("'Price Request'"), '=', $filterType);
                    })
                    ->when($filterTransactionNumber, function ($query) use ($filterTransactionNumber) {
                        return $query->where('pricerequestforms.PrfNumber', 'LIKE', '%' . $filterTransactionNumber . '%');
                    })
                    ->when($filterBDE, function ($query) use ($filterBDE) {
                        return $query->where('users.full_name', 'LIKE', '%' . $filterBDE . '%');
                    })
                    ->when($filterClient, function ($query) use ($filterClient) {
                        return $query->where('clientcompanies.Name', 'LIKE', '%' . $filterClient . '%');
                    })
                    ->when($filterDateCreated, function ($query) use ($filterDateCreated) {
                        return $query->where('pricerequestforms.created_at', 'LIKE', '%' . $filterDateCreated . '%');
                    })
                    ->when($filterDueDate, function ($query) use ($filterDueDate) {
                        return $query->where('pricerequestforms.ValidityDate', 'LIKE', '%' . $filterDueDate . '%');
                    })
                    ->when($filterStatus, function ($query) use ($filterStatus) {
                        return $query->where('pricerequestforms.Status', 'LIKE', '%' . $filterStatus . '%');
                    })
                    ->when($filterProgress, function ($query) use ($filterProgress) {
                        return $query->where('srfprogresses.name', 'LIKE', '%' . $filterProgress . '%');
                    });

        $act_data = Activity::select(
                        'activities.id', 
                        'activities.ActivityNumber as transaction_number', 
                        'users.full_name as bde',
                        'clientcompanies.Name as client', 
                        'activities.created_at as date_created', 
                        'activities.ScheduleTo as due_date', 
                        'activities.Title as details', 
                        'activities.Response as result',
                        'activities.Status as status', 
                        'activities.Status as progress', 
                        DB::raw("'Activity' as type")
                    )
                    ->leftJoin('users', 'activities.PrimaryResponsibleUserId', '=', 'users.user_id')
                    ->leftJoin('clientcompanies', 'activities.ClientId', '=', 'clientcompanies.id')
                    ->where(function ($query) use ($search) {
                        $query->where('activities.ActivityNumber', 'LIKE', '%' . $search . '%')
                            ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                            ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                            ->orWhere('activities.Title', 'LIKE', '%' . $search . '%')
                            ->orWhere('activities.Response', 'LIKE', '%' . $search . '%')
                            ->orWhere('activities.Status', 'LIKE', '%' . $search . '%')
                            ->orWhere(DB::raw("'Activity'"), 'LIKE', '%' . $search . '%');
                    })
                    ->whereBetween('activities.created_at', [$from, $to])
                    ->when($filterType, function ($query) use ($filterType) {
                        return $query->where(DB::raw("'Activity'"), '=', $filterType);
                    })
                    ->when($filterTransactionNumber, function ($query) use ($filterTransactionNumber) {
                        return $query->where('activities.ActivityNumber', 'LIKE', '%' . $filterTransactionNumber . '%');
                    })
                    ->when($filterBDE, function ($query) use ($filterBDE) {
                        return $query->where('users.full_name', 'LIKE', '%' . $filterBDE . '%');
                    })
                    ->when($filterClient, function ($query) use ($filterClient) {
                        return $query->where('clientcompanies.Name', 'LIKE', '%' . $filterClient . '%');
                    })
                    ->when($filterDateCreated, function ($query) use ($filterDateCreated) {
                        return $query->where('activities.created_at', 'LIKE', '%' . $filterDateCreated . '%');
                    })
                    ->when($filterDueDate, function ($query) use ($filterDueDate) {
                        return $query->where('activities.ScheduleTo', 'LIKE', '%' . $filterDueDate . '%');
                    })
                    ->when($filterStatus, function ($query) use ($filterStatus) {
                        return $query->where('activities.Status', 'LIKE', '%' . $filterStatus . '%');
                    })
                    ->when($filterProgress, function ($query) use ($filterProgress) {
                        return $query->where('activities.Status', 'LIKE', '%' . $filterProgress . '%');
                    });

        // Combine all queries
        $combined_query = $crr_data->union($srf_data)
                                ->union($rpe_data)
                                ->union($price_data)
                                ->union($act_data);
                                
        // Apply sorting and pagination
        $query = DB::table(DB::raw("({$combined_query->toSql()}) as combined"))
                ->mergeBindings($combined_query->getQuery())
                ->orderBy($sort, $direction);

        if ($fetchAll) {
            $transaction_data = $query->get();
        } else {
            $transaction_data = $query->paginate($entries);
        }
        $transactionNumbers = $combined_query->pluck('transaction_number')->unique();
        $uniqueBde = $combined_query->distinct()->pluck('bde')->unique();
        $uniqueClient = $combined_query->distinct()->pluck('client')->unique();
        $uniqueDateCreated = $combined_query->distinct()->pluck('date_created')->unique();
        $uniqueDueDate = $combined_query->distinct()->pluck('due_date')->unique();
        $uniqueStatus = $combined_query->distinct()->pluck('status')->unique();
        $uniqueProgress = $combined_query->distinct()->pluck('progress')->unique();
        // Prepare data for the view or JSON response
        $data = [
            'transaction_data' => $transaction_data,
            'search' => $search,
            'entries' => $entries,
            'fetchAll' => $fetchAll,
            'sort' => $sort,
            'direction' => $direction,
            'from' => $from,
            'to' => $to,
            'transactionNumbers' => $transactionNumbers,
            'uniqueBde' => $uniqueBde,
            'uniqueClient' => $uniqueClient,
            'uniqueDateCreated' => $uniqueDateCreated,
            'uniqueDueDate' => $uniqueDueDate,
            'uniqueStatus' => $uniqueStatus,
            'uniqueProgress' => $uniqueProgress
        ];

        if ($fetchAll) {
            return response()->json($data); // Return all data as JSON
        } else {
            return view('reports.transaction_summary', $data); // Return the view with paginated data
        }
    }

    // Export Transaction Activity
    public function exportTransactionActivity(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'client');
        $direction = $request->get('direction', 'desc');
        $from = $request->input('from') ?: now()->startOfMonth()->format('Y-m-d');
        $to = $request->input('to') ?: now()->endOfMonth()->format('Y-m-d');

        // Ensure sort and direction are valid
        $validSorts = ['status', 'client', 'transaction_number', 'date_created'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'client';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        // Reuse the combined query logic
        $crr_data = CustomerRequirement::select(
                    DB::raw("'Customer Requirement' as type"),
                    'customerrequirements.CrrNumber as transaction_number', 
                    'users.full_name as bde',
                    'clientcompanies.Name as client', 
                    DB::raw("DATE_FORMAT(customerrequirements.DateCreated, '%b. %d, %Y') as date_created"),
                    DB::raw("DATE_FORMAT(customerrequirements.DueDate, '%b. %d, %Y') as due_date"),
                    'customerrequirements.DetailsOfRequirement as details', 
                    'customerrequirements.Recommendation as result', 
                    DB::raw("
                        CASE 
                            WHEN customerrequirements.Status = 10 THEN 'Open' 
                            WHEN customerrequirements.Status = 20 THEN 'Closed'
                            ELSE customerrequirements.Status
                        END as status
                    "),
                    'srfprogresses.name as progress'  
                )
                ->leftJoin('users', 'customerrequirements.PrimarySalesPersonId', '=', 'users.user_id')
                ->leftJoin('clientcompanies', 'customerrequirements.ClientId', '=', 'clientcompanies.id')
                ->leftJoin('srfprogresses', 'customerrequirements.Progress', '=', 'srfprogresses.id')
                ->where(function ($query) use ($search) {
                    $query->where('customerrequirements.CrrNumber', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                        ->orWhere('customerrequirements.DetailsOfRequirement', 'LIKE', '%' . $search . '%')
                        ->orWhere('customerrequirements.Recommendation', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("CASE 
                                            WHEN customerrequirements.Status = 10 THEN 'Open' 
                                            WHEN customerrequirements.Status = 20 THEN 'Closed'
                                            ELSE customerrequirements.Status
                                        END"), 'LIKE', '%' . $search . '%') 
                        ->orWhere('srfprogresses.name', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("'Customer Requirement'"), 'LIKE', '%' . $search . '%');
                })
                ->whereBetween('customerrequirements.DateCreated', [$from, $to]);

        $srf_data = SampleRequest::select(
                    DB::raw("'Sample Request' as type"),
                    'samplerequests.SrfNumber as transaction_number', 
                    'users.full_name as bde',
                    'clientcompanies.Name as client', 
                    DB::raw("DATE_FORMAT(samplerequests.created_at, '%b. %d, %Y') as date_created"),
                    DB::raw("DATE_FORMAT(samplerequests.DateRequired, '%b. %d, %Y') as due_date"),
                    'samplerequests.InternalRemarks as details', 
                    'samplerequests.Disposition as result',  
                    DB::raw("
                        CASE 
                            WHEN samplerequests.Status = 10 THEN 'Open' 
                            WHEN samplerequests.Status = 20 THEN 'Closed'
                            ELSE samplerequests.Status
                        END as status
                    "),
                    'srfprogresses.name as progress'                 
                )
                ->leftJoin('users', 'samplerequests.PrimarySalesPersonId', '=', 'users.user_id')
                ->leftJoin('clientcompanies', 'samplerequests.ClientId', '=', 'clientcompanies.id')
                ->leftJoin('srfprogresses', 'samplerequests.Progress', '=', 'srfprogresses.id')
                ->where(function ($query) use ($search) {
                    $query->where('samplerequests.SrfNumber', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                        ->orWhere('samplerequests.InternalRemarks', 'LIKE', '%' . $search . '%')
                        ->orWhere('samplerequests.Disposition', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("CASE 
                                            WHEN samplerequests.Status = 10 THEN 'Open' 
                                            WHEN samplerequests.Status = 20 THEN 'Closed'
                                            ELSE samplerequests.Status
                                        END"), 'LIKE', '%' . $search . '%') 
                        ->orWhere('srfprogresses.name', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("'Sample Request'"), 'LIKE', '%' . $search . '%');
                })
                ->whereBetween('samplerequests.created_at', [$from, $to]);

        $rpe_data = RequestProductEvaluation::select(
                    DB::raw("'Request Product Evaluation' as type"),
                    'requestproductevaluations.RpeNumber as transaction_number', 
                    'users.full_name as bde',
                    'clientcompanies.Name as client', 
                    DB::raw("DATE_FORMAT(requestproductevaluations.CreatedDate, '%b. %d, %Y') as date_created"),
                    DB::raw("DATE_FORMAT(requestproductevaluations.DueDate, '%b. %d, %Y') as due_date"),
                    'requestproductevaluations.ObjectiveForRpeProject as details', 
                    'requestproductevaluations.RpeResult as result',
                    DB::raw("
                        CASE 
                            WHEN requestproductevaluations.Status = 10 THEN 'Open' 
                            WHEN requestproductevaluations.Status = 20 THEN 'Closed'
                            ELSE requestproductevaluations.Status
                        END as status
                    "),
                    'srfprogresses.name as progress'          
                )
                ->leftJoin('users', 'requestproductevaluations.PrimarySalesPersonId', '=', 'users.user_id')
                ->leftJoin('clientcompanies', 'requestproductevaluations.ClientId', '=', 'clientcompanies.id')
                ->leftJoin('srfprogresses', 'requestproductevaluations.Progress', '=', 'srfprogresses.id')
                ->where(function ($query) use ($search) {
                    $query->where('requestproductevaluations.RpeNumber', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                        ->orWhere('requestproductevaluations.ObjectiveForRpeProject', 'LIKE', '%' . $search . '%')
                        ->orWhere('requestproductevaluations.RpeResult', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("CASE 
                                            WHEN requestproductevaluations.Status = 10 THEN 'Open' 
                                            WHEN requestproductevaluations.Status = 20 THEN 'Closed'
                                            ELSE requestproductevaluations.Status
                                        END"), 'LIKE', '%' . $search . '%') 
                        ->orWhere('srfprogresses.name', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("'Request Product Evaluation'"), 'LIKE', '%' . $search . '%');
                })
                ->whereBetween('requestproductevaluations.CreatedDate', [$from, $to]);

        $price_data = PriceMonitoring::select(
                    DB::raw("'Price Request' as type"),
                    'pricerequestforms.PrfNumber as transaction_number', 
                    'users.full_name as bde',
                    'clientcompanies.Name as client', 
                    DB::raw("DATE_FORMAT(pricerequestforms.created_at, '%b. %d, %Y') as date_created"),
                    DB::raw("DATE_FORMAT(pricerequestforms.ValidityDate, '%b. %d, %Y') as due_date"),
                    'pricerequestforms.Remarks as details', 
                    'pricerequestforms.DispositionRemarks as result',
                    DB::raw("
                        CASE 
                            WHEN pricerequestforms.Status = 10 THEN 'Open' 
                            WHEN pricerequestforms.Status = 20 THEN 'Closed'
                            ELSE pricerequestforms.Status
                        END as staus
                    "),
                    'srfprogresses.name as progress'
                )
                ->leftJoin('users', 'pricerequestforms.PrimarySalesPersonId', '=', 'users.user_id')
                ->leftJoin('clientcompanies', 'pricerequestforms.ClientId', '=', 'clientcompanies.id')
                ->leftJoin('srfprogresses', 'pricerequestforms.Progress', '=', 'srfprogresses.id')
                ->where(function ($query) use ($search) {
                    $query->where('pricerequestforms.PrfNumber', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                        ->orWhere('pricerequestforms.Remarks', 'LIKE', '%' . $search . '%')
                        ->orWhere('pricerequestforms.DispositionRemarks', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("CASE 
                                            WHEN pricerequestforms.Status = 10 THEN 'Open' 
                                            WHEN pricerequestforms.Status = 20 THEN 'Closed'
                                            ELSE pricerequestforms.Status
                                        END"), 'LIKE', '%' . $search . '%') 
                        ->orWhere('srfprogresses.name', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("'Price Request'"), 'LIKE', '%' . $search . '%');
                })
                ->whereBetween('pricerequestforms.created_at', [$from, $to]);

        $act_data = Activity::select(
                    DB::raw("'Activity' as type"),
                    'activities.ActivityNumber as transaction_number', 
                    'users.full_name as bde',
                    'clientcompanies.Name as client', 
                    DB::raw("DATE_FORMAT(activities.created_at, '%b. %d, %Y') as date_created"),
                    DB::raw("DATE_FORMAT(activities.ScheduleTo, '%b. %d, %Y') as due_date"),
                    'activities.Title as details', 
                    'activities.Response as result',
                    DB::raw("
                        CASE 
                            WHEN activities.Status = 10 THEN 'Open' 
                            WHEN activities.Status = 20 THEN 'Closed'
                            ELSE activities.Status
                        END as status
                    "),
                    DB::raw("
                        CASE 
                            WHEN activities.Status = 10 THEN 'Open' 
                            WHEN activities.Status = 20 THEN 'Closed'
                            ELSE srfprogresses.name
                        END as progress
                    ")  
                )
                ->leftJoin('users', 'activities.PrimaryResponsibleUserId', '=', 'users.user_id')
                ->leftJoin('clientcompanies', 'activities.ClientId', '=', 'clientcompanies.id')
                ->leftJoin('srfprogresses', 'activities.Status', '=', 'srfprogresses.id')  // Join with srfprogresses
                ->where(function ($query) use ($search) {
                    $query->where('activities.ActivityNumber', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                        ->orWhere('activities.Title', 'LIKE', '%' . $search . '%')
                        ->orWhere('activities.Response', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("CASE 
                                            WHEN activities.Status = 10 THEN 'Open' 
                                            WHEN activities.Status = 20 THEN 'Closed'
                                            ELSE activities.Status
                                        END"), 'LIKE', '%' . $search . '%') 
                        ->orWhere(DB::raw("CASE 
                                            WHEN activities.Status = 10 THEN 'Open' 
                                            WHEN activities.Status = 20 THEN 'Closed'
                                            ELSE srfprogresses.name
                                        END"), 'LIKE', '%' . $search . '%') 
                        ->orWhere(DB::raw("'Activity'"), 'LIKE', '%' . $search . '%');
                })
                ->whereBetween('activities.created_at', [$from, $to]);


        // Combine all queries
        $combined_query = $crr_data->union($srf_data)
                                ->union($rpe_data)
                                ->union($price_data)
                                ->union($act_data);

        // Apply sorting
        $query = DB::table(DB::raw("({$combined_query->toSql()}) as combined"))
                    ->mergeBindings($combined_query->getQuery())
                    ->orderBy($sort, $direction);
                    
        // Fetch all data
        $transaction_data = $query->get();
        // Pass the data to the export class
        return Excel::download(new TransactionActivityExport($transaction_data), 'transaction_activity.xlsx');
    }

    // Copy Transaction Activity
    public function copyTransactionActivity(Request $request)
    {
        // Extract query parameters and validate as in your original method
        $search = $request->input('search');
        $sort = $request->get('sort', 'client');
        $direction = $request->get('direction', 'desc');
        $from = $request->input('from') ?: now()->startOfMonth()->format('Y-m-d');
        $to = $request->input('to') ?: now()->endOfMonth()->format('Y-m-d');

        // Ensure sort and direction are valid
        $validSorts = ['status', 'client', 'transaction_number', 'date_created'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'client';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        // Reuse the combined query logic
        $crr_data = CustomerRequirement::select(
                    DB::raw("'Customer Requirement' as type"),
                    'customerrequirements.CrrNumber as transaction_number', 
                    'users.full_name as bde',
                    'clientcompanies.Name as client', 
                    DB::raw("DATE_FORMAT(customerrequirements.DateCreated, '%b. %d, %Y') as date_created"),
                    DB::raw("DATE_FORMAT(customerrequirements.DueDate, '%b. %d, %Y') as due_date"),
                    'customerrequirements.DetailsOfRequirement as details', 
                    'customerrequirements.Recommendation as result', 
                    DB::raw("
                        CASE 
                            WHEN customerrequirements.Status = 10 THEN 'Open' 
                            WHEN customerrequirements.Status = 20 THEN 'Closed'
                            ELSE customerrequirements.Status
                        END as status
                    "),
                    'srfprogresses.name as progress'  
                )
                ->leftJoin('users', 'customerrequirements.PrimarySalesPersonId', '=', 'users.user_id')
                ->leftJoin('clientcompanies', 'customerrequirements.ClientId', '=', 'clientcompanies.id')
                ->leftJoin('srfprogresses', 'customerrequirements.Progress', '=', 'srfprogresses.id')
                ->where(function ($query) use ($search) {
                    $query->where('customerrequirements.CrrNumber', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                        ->orWhere('customerrequirements.DetailsOfRequirement', 'LIKE', '%' . $search . '%')
                        ->orWhere('customerrequirements.Recommendation', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("CASE 
                                            WHEN customerrequirements.Status = 10 THEN 'Open' 
                                            WHEN customerrequirements.Status = 20 THEN 'Closed'
                                            ELSE customerrequirements.Status
                                        END"), 'LIKE', '%' . $search . '%') 
                        ->orWhere('srfprogresses.name', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("'Customer Requirement'"), 'LIKE', '%' . $search . '%');
                })
                ->whereBetween('customerrequirements.DateCreated', [$from, $to]);

        $srf_data = SampleRequest::select(
                    DB::raw("'Sample Request' as type"),
                    'samplerequests.SrfNumber as transaction_number', 
                    'users.full_name as bde',
                    'clientcompanies.Name as client', 
                    DB::raw("DATE_FORMAT(samplerequests.created_at, '%b. %d, %Y') as date_created"),
                    DB::raw("DATE_FORMAT(samplerequests.DateRequired, '%b. %d, %Y') as due_date"),
                    'samplerequests.InternalRemarks as details', 
                    'samplerequests.Disposition as result',  
                    DB::raw("
                        CASE 
                            WHEN samplerequests.Status = 10 THEN 'Open' 
                            WHEN samplerequests.Status = 20 THEN 'Closed'
                            ELSE samplerequests.Status
                        END as status
                    "),
                    'srfprogresses.name as progress'                 
                )
                ->leftJoin('users', 'samplerequests.PrimarySalesPersonId', '=', 'users.user_id')
                ->leftJoin('clientcompanies', 'samplerequests.ClientId', '=', 'clientcompanies.id')
                ->leftJoin('srfprogresses', 'samplerequests.Progress', '=', 'srfprogresses.id')
                ->where(function ($query) use ($search) {
                    $query->where('samplerequests.SrfNumber', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                        ->orWhere('samplerequests.InternalRemarks', 'LIKE', '%' . $search . '%')
                        ->orWhere('samplerequests.Disposition', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("CASE 
                                            WHEN samplerequests.Status = 10 THEN 'Open' 
                                            WHEN samplerequests.Status = 20 THEN 'Closed'
                                            ELSE samplerequests.Status
                                        END"), 'LIKE', '%' . $search . '%') 
                        ->orWhere('srfprogresses.name', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("'Sample Request'"), 'LIKE', '%' . $search . '%');
                })
                ->whereBetween('samplerequests.created_at', [$from, $to]);

        $rpe_data = RequestProductEvaluation::select(
                    DB::raw("'Request Product Evaluation' as type"),
                    'requestproductevaluations.RpeNumber as transaction_number', 
                    'users.full_name as bde',
                    'clientcompanies.Name as client', 
                    DB::raw("DATE_FORMAT(requestproductevaluations.CreatedDate, '%b. %d, %Y') as date_created"),
                    DB::raw("DATE_FORMAT(requestproductevaluations.DueDate, '%b. %d, %Y') as due_date"),
                    'requestproductevaluations.ObjectiveForRpeProject as details', 
                    'requestproductevaluations.RpeResult as result',
                    DB::raw("
                        CASE 
                            WHEN requestproductevaluations.Status = 10 THEN 'Open' 
                            WHEN requestproductevaluations.Status = 20 THEN 'Closed'
                            ELSE requestproductevaluations.Status
                        END as status
                    "),
                    'srfprogresses.name as progress'          
                )
                ->leftJoin('users', 'requestproductevaluations.PrimarySalesPersonId', '=', 'users.user_id')
                ->leftJoin('clientcompanies', 'requestproductevaluations.ClientId', '=', 'clientcompanies.id')
                ->leftJoin('srfprogresses', 'requestproductevaluations.Progress', '=', 'srfprogresses.id')
                ->where(function ($query) use ($search) {
                    $query->where('requestproductevaluations.RpeNumber', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                        ->orWhere('requestproductevaluations.ObjectiveForRpeProject', 'LIKE', '%' . $search . '%')
                        ->orWhere('requestproductevaluations.RpeResult', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("CASE 
                                            WHEN requestproductevaluations.Status = 10 THEN 'Open' 
                                            WHEN requestproductevaluations.Status = 20 THEN 'Closed'
                                            ELSE requestproductevaluations.Status
                                        END"), 'LIKE', '%' . $search . '%') 
                        ->orWhere('srfprogresses.name', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("'Request Product Evaluation'"), 'LIKE', '%' . $search . '%');
                })
                ->whereBetween('requestproductevaluations.CreatedDate', [$from, $to]);

        $price_data = PriceMonitoring::select(
                    DB::raw("'Price Request' as type"),
                    'pricerequestforms.PrfNumber as transaction_number', 
                    'users.full_name as bde',
                    'clientcompanies.Name as client', 
                    DB::raw("DATE_FORMAT(pricerequestforms.created_at, '%b. %d, %Y') as date_created"),
                    DB::raw("DATE_FORMAT(pricerequestforms.ValidityDate, '%b. %d, %Y') as due_date"),
                    'pricerequestforms.Remarks as details', 
                    'pricerequestforms.DispositionRemarks as result',
                    DB::raw("
                        CASE 
                            WHEN pricerequestforms.Status = 10 THEN 'Open' 
                            WHEN pricerequestforms.Status = 20 THEN 'Closed'
                            ELSE pricerequestforms.Status
                        END as staus
                    "),
                    'srfprogresses.name as progress'
                )
                ->leftJoin('users', 'pricerequestforms.PrimarySalesPersonId', '=', 'users.user_id')
                ->leftJoin('clientcompanies', 'pricerequestforms.ClientId', '=', 'clientcompanies.id')
                ->leftJoin('srfprogresses', 'pricerequestforms.Progress', '=', 'srfprogresses.id')
                ->where(function ($query) use ($search) {
                    $query->where('pricerequestforms.PrfNumber', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                        ->orWhere('pricerequestforms.Remarks', 'LIKE', '%' . $search . '%')
                        ->orWhere('pricerequestforms.DispositionRemarks', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("CASE 
                                            WHEN pricerequestforms.Status = 10 THEN 'Open' 
                                            WHEN pricerequestforms.Status = 20 THEN 'Closed'
                                            ELSE pricerequestforms.Status
                                        END"), 'LIKE', '%' . $search . '%') 
                        ->orWhere('srfprogresses.name', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("'Price Request'"), 'LIKE', '%' . $search . '%');
                })
                ->whereBetween('pricerequestforms.created_at', [$from, $to]);

        $act_data = Activity::select(
                    DB::raw("'Activity' as type"),
                    'activities.ActivityNumber as transaction_number', 
                    'users.full_name as bde',
                    'clientcompanies.Name as client', 
                    DB::raw("DATE_FORMAT(activities.created_at, '%b. %d, %Y') as date_created"),
                    DB::raw("DATE_FORMAT(activities.ScheduleTo, '%b. %d, %Y') as due_date"),
                    'activities.Title as details', 
                    'activities.Response as result',
                    DB::raw("
                        CASE 
                            WHEN activities.Status = 10 THEN 'Open' 
                            WHEN activities.Status = 20 THEN 'Closed'
                            ELSE activities.Status
                        END as status
                    "),
                    DB::raw("
                        CASE 
                            WHEN activities.Status = 10 THEN 'Open' 
                            WHEN activities.Status = 20 THEN 'Closed'
                            ELSE srfprogresses.name
                        END as progress
                    ")  
                )
                ->leftJoin('users', 'activities.PrimaryResponsibleUserId', '=', 'users.user_id')
                ->leftJoin('clientcompanies', 'activities.ClientId', '=', 'clientcompanies.id')
                ->leftJoin('srfprogresses', 'activities.Status', '=', 'srfprogresses.id')  // Join with srfprogresses
                ->where(function ($query) use ($search) {
                    $query->where('activities.ActivityNumber', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                        ->orWhere('activities.Title', 'LIKE', '%' . $search . '%')
                        ->orWhere('activities.Response', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("CASE 
                                            WHEN activities.Status = 10 THEN 'Open' 
                                            WHEN activities.Status = 20 THEN 'Closed'
                                            ELSE activities.Status
                                        END"), 'LIKE', '%' . $search . '%') 
                        ->orWhere(DB::raw("CASE 
                                            WHEN activities.Status = 10 THEN 'Open' 
                                            WHEN activities.Status = 20 THEN 'Closed'
                                            ELSE srfprogresses.name
                                        END"), 'LIKE', '%' . $search . '%') 
                        ->orWhere(DB::raw("'Activity'"), 'LIKE', '%' . $search . '%');
                })
                ->whereBetween('activities.created_at', [$from, $to]);

        // Combine the data
        $data = $crr_data->union($srf_data)
            ->union($rpe_data)
            ->union($price_data)
            ->union($act_data)
            ->orderBy($sort, $direction)
            ->get();            

        // Convert the data to CSV format
        $csv = "Type\tTransaction Number\tBDE\tClient\tDate Created\tDue Date\tDetails\tResult\tStatus\tProgress\n";
        foreach ($data as $item) {
            $csv .= "{$item->type}\t{$item->transaction_number}\t{$item->bde}\t{$item->client}\t{$item->date_created}\t{$item->due_date}\t" . 
                    str_replace([',', "\n", "\r"], [' ', ' ', ' '], $item->details) . 
                    "\t{$item->result}\t{$item->status}\t{$item->progress}\n";
        }

        return response()->json(['data' => $csv]);
    }

}
