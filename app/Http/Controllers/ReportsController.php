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

        // Optimize base query with necessary joins only
        $query = PriceMonitoring::query()
            ->with([
                'primarySalesPerson:user_id,full_name', 
                'client:id,name', 
                'priceRequestProduct:id,code', 
                'priceRequestProduct:id,ProductRmc,IsalesOfferedPrice,QuantityRequired,IsalesMargin,IsalesMarginPercentage', 
                'paymentterms:id,Name'
            ])
            ->when($search, function ($query) use ($search) {
                $query->where('DateRequested', 'LIKE', '%' . $search . '%')
                    ->orWhere('ShipmentTerm', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('primarySalesPerson', function ($q) use ($search) {
                        $q->where('full_name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('Name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('products', function ($q) use ($search) {
                        $q->where('code', 'LIKE', '%' . $search . '%');
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
        // dd($query->take(1));
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
                'allPrimarySalesPersons', 'allProductRmc', 'allDates', 'allClients',
                'allShipments', 'allPayments', 'allQuantity', 'allAccepted', 'allRemarks', 'allProducts', 'allOfferedPrice', 'allMargin', 'allPercentMargin', 'totalMargins'
            ));
        }
    }

    // Export Price Requests
    public function exportPriceRequest(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'DateRequested'); // Default to 'DateRequested' if no sort is specified
        $direction = $request->get('direction', 'asc'); // Default to ascending order

        // Define a list of valid columns for sorting
        $validSortColumns = ['DateRequested', 'PrimarySalesPersonId', 'ClientId', 'ProductCode', 'ProductRmc', 'OfferedPrice', 'ShipmentTerm', 'PaymentTerm', 'QuantityRequired', 'Margin', 'MarginPercentage', 'TotalMargin', 'IsAccepted', 'Remarks'];

        // Validate sort column
        if (!in_array($sort, $validSortColumns)) {
            $sort = 'DateRequested'; // Default to 'DateRequested' if invalid
        }

        // Fetch all records based on search, sort, and direction
        $priceRequests = PriceMonitoring::with([
                'primarySalesPerson:user_id,full_name', 
                'client:id,name', 
                'products',
                'paymentterms:id,Name'
            ])// Eager load relationships
            ->leftJoin('pricerequestproducts', 'pricerequestproducts.id', '=', 'pricerequestforms.id')
            ->where(function ($query) use ($search) {
                $query->where('DateRequested', 'LIKE', '%' . $search . '%')
                    ->orWhere('PrimarySalesPersonId', 'LIKE', '%' . $search . '%');
            })
            ->orderBy($sort, $direction)
            ->get(); // Fetch all results
     
        // Convert data to an array format that can be easily handled by JavaScript
        return response()->json($priceRequests);
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

        // Ensure sort and direction are valid
        $validSorts = ['status', 'client', 'transaction_number', 'date_created'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'client'; // Default sort field
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        // Query and normalize data from all models
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
                        'customerrequirements.Progress as progress',
                        DB::raw("'Customer Requirement' as type")
                    )
                    ->leftJoin('users', 'customerrequirements.PrimarySalesPersonId', '=', 'users.user_id')
                    ->leftJoin('clientcompanies', 'customerrequirements.ClientId', '=', 'clientcompanies.id')
                    ->where(function ($query) use ($search) {
                        $query->where('customerrequirements.CrrNumber', 'LIKE', '%' . $search . '%')
                            ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                            ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                            ->orWhere('customerrequirements.DetailsOfRequirement', 'LIKE', '%' . $search . '%')
                            ->orWhere('customerrequirements.Recommendation', 'LIKE', '%' . $search . '%')
                            ->orWhere('customerrequirements.Status', 'LIKE', '%' . $search . '%')
                            ->orWhere('customerrequirements.Progress', 'LIKE', '%' . $search . '%')
                            ->orWhere(DB::raw("'Customer Requirement'"), 'LIKE', '%' . $search . '%');
                    })
                    ->whereBetween('customerrequirements.DateCreated', [$from, $to]);

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
                        'samplerequests.Progress as progress',
                        DB::raw("'Sample Request' as type")
                    )
                    ->leftJoin('users', 'samplerequests.PrimarySalesPersonId', '=', 'users.user_id')
                    ->leftJoin('clientcompanies', 'samplerequests.ClientId', '=', 'clientcompanies.id')
                    ->where(function ($query) use ($search) {
                        $query->where('samplerequests.SrfNumber', 'LIKE', '%' . $search . '%')
                            ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                            ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                            ->orWhere('samplerequests.InternalRemarks', 'LIKE', '%' . $search . '%')
                            ->orWhere('samplerequests.Disposition', 'LIKE', '%' . $search . '%')
                            ->orWhere('samplerequests.Status', 'LIKE', '%' . $search . '%')
                            ->orWhere('samplerequests.Progress', 'LIKE', '%' . $search . '%')
                            ->orWhere(DB::raw("'Sample Request'"), 'LIKE', '%' . $search . '%');
                    })
                    ->whereBetween('samplerequests.created_at', [$from, $to]);

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
                        'requestproductevaluations.Progress as progress',
                        DB::raw("'Request Product Evaluation' as type")
                    )
                    ->leftJoin('users', 'requestproductevaluations.PrimarySalesPersonId', '=', 'users.user_id')
                    ->leftJoin('clientcompanies', 'requestproductevaluations.ClientId', '=', 'clientcompanies.id')
                    ->where(function ($query) use ($search) {
                        $query->where('requestproductevaluations.RpeNumber', 'LIKE', '%' . $search . '%')
                            ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                            ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                            ->orWhere('requestproductevaluations.ObjectiveForRpeProject', 'LIKE', '%' . $search . '%')
                            ->orWhere('requestproductevaluations.RpeResult', 'LIKE', '%' . $search . '%')
                            ->orWhere('requestproductevaluations.Status', 'LIKE', '%' . $search . '%')
                            ->orWhere('requestproductevaluations.Progress', 'LIKE', '%' . $search . '%')
                            ->orWhere(DB::raw("'Request Product Evaluation'"), 'LIKE', '%' . $search . '%');
                    })
                    ->whereBetween('requestproductevaluations.CreatedDate', [$from, $to]);

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
                        'pricerequestforms.Status as progress',
                        DB::raw("'Price Request' as type")
                    )
                    ->leftJoin('users', 'pricerequestforms.PrimarySalesPersonId', '=', 'users.user_id')
                    ->leftJoin('clientcompanies', 'pricerequestforms.ClientId', '=', 'clientcompanies.id')
                    ->where(function ($query) use ($search) {
                        $query->where('pricerequestforms.PrfNumber', 'LIKE', '%' . $search . '%')
                            ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                            ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                            ->orWhere('pricerequestforms.Remarks', 'LIKE', '%' . $search . '%')
                            ->orWhere('pricerequestforms.DispositionRemarks', 'LIKE', '%' . $search . '%')
                            ->orWhere('pricerequestforms.Status', 'LIKE', '%' . $search . '%')
                            ->orWhere(DB::raw("'Price Request'"), 'LIKE', '%' . $search . '%');
                    })
                    ->whereBetween('pricerequestforms.created_at', [$from, $to]);
        
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
                    ->whereBetween('activities.created_at', [$from, $to]);
        

        // Combine the queries with a subquery for ordering
        $combined_data = DB::table(DB::raw("({$crr_data->union($srf_data)->union($rpe_data)->union($act_data)->union($price_data)->toSql()}) as sub"))
                            ->mergeBindings($crr_data->union($srf_data)->union($rpe_data)->union($act_data)->union($price_data)->getQuery())
                            ->orderBy($sort, $direction);

        // Fetch results based on the fetchAll flag
        if ($fetchAll) {
            $transaction_data = $combined_data->get();
        } else {
            $transaction_data = $combined_data->paginate($entries);
        }

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
        ];

        if ($fetchAll) {
            return response()->json($data); // Return all data as JSON
        } else {
            return view('reports.transaction_summary', $data); // Return the view with paginated data
        }
    }
}
