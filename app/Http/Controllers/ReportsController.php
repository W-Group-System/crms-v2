<?php

namespace App\Http\Controllers;

use App\Activity;
use App\CustomerRequirement;
use App\PriceMonitoring;
use App\RequestProductEvaluation;
use App\SampleRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    // Price Request
    public function price_summary(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'DateRequested'); 
        $direction = $request->get('direction', 'desc'); // Default to descending order
        $fetchAll = $request->input('fetch_all', false); // Get the fetch_all parameter
        $entries = $request->input('number_of_entries', 10); // Default to 10 entries if not provided

        // Ensure sort and direction are valid
        $validSorts = ['DateRequested', 'ShipmentTerm'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'DateRequested';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        // Build the query
        $query = PriceMonitoring::where(function ($query) use ($search) {
                if ($search) {
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
                }
            })
            ->orderBy($sort, $direction);

        // Handle fetchAll logic
        if ($fetchAll) {
            $price_requests = $query->get(); // Fetch all results
            return response()->json($price_requests); // Return JSON response for copying
        } else {
            $price_requests = $query->paginate($entries); // Paginate with the specified number of entries
            return view('reports.price_summary', [
                'search' => $search,
                'price_requests' => $price_requests,
                'entries' => $entries,
                'fetchAll' => $fetchAll,
                'sort' => $sort,
                'direction' => $direction,
            ]);
        }
    }

    // Export Price Requests
    public function exportPriceRequest(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'DateRequested'); // Default to 'DateRequested' if no sort is specified
        $direction = $request->get('direction', 'asc'); // Default to ascending order

        // Define a list of valid columns for sorting
        $validSortColumns = ['DateRequested', 'ClientId'];

        // Validate sort column
        if (!in_array($sort, $validSortColumns)) {
            $sort = 'DateRequested'; // Default to 'DateRequested' if invalid
        }

        // Fetch all records based on search, sort, and direction
        $priceRequest = PriceMonitoring::where(function ($query) use ($search) {
                $query->where('DateRequested', 'LIKE', '%' . $search . '%')
                    ->orWhere('ClientId', 'LIKE', '%' . $search . '%');
            })
            ->orderBy($sort, $direction)
            ->get(); // Fetch all results

        // Convert data to an array format that can be easily handled by JavaScript
        return response()->json($priceRequest);
    }

    // Transaction/Activity
    public function transaction_summary(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'client'); 
        $direction = $request->get('direction', 'desc');
        $fetchAll = $request->input('fetch_all', false);
        $entries = $request->input('number_of_entries', 10);
        $from = $request->input('from');
        $to = $request->input('to');

        // Ensure sort and direction are valid
        $validSorts = ['Status', 'ClientId'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'Status'; // Set a default sort field
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        // Query and normalize data from all models
        $crr_data = CustomerRequirement::select(
                    'customerrequirements.id', 
                    'customerrequirements.CrrNumber as transaction_number', 
                    'users.full_name as bde', // Include full_name from the related User
                    'clientcompanies.Name as client', 
                    'customerrequirements.DateCreated as date_created', 
                    'customerrequirements.DueDate as due_date', 
                    'customerrequirements.DetailsOfRequirement as details', 
                    'customerrequirements.Recommendation as result', 
                    'customerrequirements.Status as status', 
                    'customerrequirements.Progress as progress',
                    DB::raw("'Customer Requirement' as type")
                )
                ->leftJoin('users', 'customerrequirements.PrimarySalesPersonId', '=', 'users.user_id') // Use 'id' for User model
                ->leftJoin('clientcompanies', 'customerrequirements.ClientId', '=', 'clientcompanies.id') // Use 'id' for User model
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
                ->when($from, function ($query) use ($from) {
                    return $query->whereDate('customerrequirements.DateCreated', '>=', $from);
                })
                ->when($to, function ($query) use ($to) {
                    return $query->whereDate('customerrequirements.DateCreated', '<=', $to);
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
                            'samplerequests.Progress as progress',
                            DB::raw("'Sample Request' as type")
                        )
                        ->leftJoin('users', 'samplerequests.PrimarySalesPersonId', '=', 'users.user_id') // Use 'id' for User model
                        ->leftJoin('clientcompanies', 'samplerequests.ClientId', '=', 'clientcompanies.id') // Use 'id' for User model
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
                        ->when($from, function ($query) use ($from) {
                            return $query->whereDate('samplerequests.created_at', '>=', $from);
                        })
                        ->when($to, function ($query) use ($to) {
                            return $query->whereDate('samplerequests.created_at', '<=', $to);
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
                            'requestproductevaluations.Progress as progress',
                            DB::raw("'Request Product Evaluation' as type")
                        )
                        ->leftJoin('users', 'requestproductevaluations.PrimarySalesPersonId', '=', 'users.user_id') // Use 'id' for User model
                        ->leftJoin('clientcompanies', 'requestproductevaluations.ClientId', '=', 'clientcompanies.id') // Use 'id' for User model
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
                        ->when($from, function ($query) use ($from) {
                            return $query->whereDate('requestproductevaluations.CreatedDate', '>=', $from);
                        })
                        ->when($to, function ($query) use ($to) {
                            return $query->whereDate('requestproductevaluations.CreatedDate', '<=', $to);
                        });                
                   
        // Combine the queries with a subquery for ordering
        $combined_data = DB::table(DB::raw("({$crr_data->union($srf_data)->union($rpe_data)->toSql()}) as sub"))
                            ->mergeBindings($crr_data->union($srf_data)->union($rpe_data)->getQuery())
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
