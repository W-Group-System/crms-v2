<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Client;
use App\CustomerRequirement;
use App\Exports\SampleDispatchReportExport;
use App\PaymentTerms;
use App\PriceMonitoring;
use App\PriceRequestProduct;
use App\RequestProductEvaluation;
use App\SampleRequest;
use App\SampleRequestProduct;
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

        $from = $request->input('from') ?: now()->startOfMonth()->format('Y-m-d');
        $to = $request->input('to') ?: now()->endOfMonth()->format('Y-m-d');

        // Get filter inputs
        $filterDate = $request->input('filter_date');
        $filterPRF = $request->input('filter_prf');
        $filterAccount = $request->input('filter_account_manager');
        $filterClient = $request->input('filter_client');
        $filterProduct = $request->input('filter_product');
        $filterRMC = $request->input('filter_rmc');
        $filterOffered = $request->input('filter_offered');
        $filterShipment = $request->input('filter_shipment');
        $filterPayment = $request->input('filter_payment');
        $filterQuantity = $request->input('filter_quantity');
        $filterMargin = $request->input('filter_margin');
        $filterPercentMargin = $request->input('filter_percent_margin');
        $filterTotalMargin = $request->input('filter_total_margin');
        $filterAccepted = $request->input('filter_accepted');
        $filterRemarks = $request->input('filter_remarks');

        $validSorts = ['DateRequested', 'PrimarySalesPersonId', 'ClientId', 'ProductCode', 'ProductRmc', 'OfferedPrice', 'ShipmentTerm', 'PaymentTerm', 'QuantityRequired', 'Margin', 'MarginPercentage', 'TotalMargin', 'IsAccepted', 'Remarks'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'DateRequested';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        // Build the query
        $query = PriceMonitoring::with([
            'primarySalesPerson:user_id,full_name', 
            'client:id,name', 
            'priceRequestProduct:id,ProductId,ProductRmc,IsalesOfferedPrice,QuantityRequired,IsalesMargin,IsalesMarginPercentage', 
            'paymentterms:id,Name',
            'products:ProductId,code'
        ])
        ->leftJoin('pricerequestproducts', 'pricerequestproducts.PriceRequestFormId', '=', 'pricerequestforms.id')
        ->leftJoin('products', 'products.id', '=', 'pricerequestproducts.ProductId')
        ->leftJoin('clientpaymentterms', 'clientpaymentterms.id', '=', 'pricerequestforms.PaymentTermId')
        ->leftJoin('users as primarySalesPerson', 'primarySalesPerson.user_id', '=', 'pricerequestforms.PrimarySalesPersonId')
        ->leftJoin('clientcompanies as client', 'client.id', '=', 'pricerequestforms.ClientId')
        ->select('pricerequestforms.*', 'pricerequestproducts.*', 'products.*', 'clientpaymentterms.Name as PaymentTermName')
        ->whereBetween('pricerequestforms.DateRequested', [$from, $to])
        ->where(function ($query) use ($search) {
            $query->where('pricerequestforms.DateRequested', 'LIKE', "%$search%")
                ->orWhere('pricerequestforms.PrfNumber', 'LIKE', "%$search%")
                ->orWhere('pricerequestforms.ShipmentTerm', 'LIKE', "%$search%")
                ->orWhereHas('products', function ($q) use ($search) {
                    $q->where('code', 'LIKE', "%$search%");
                })
                ->orWhereHas('primarySalesPerson', function ($q) use ($search) {
                    $q->where('full_name', 'LIKE', "%$search%");
                })
                ->orWhereHas('client', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                })
                ->orWhereHas('priceRequestProduct', function ($q) use ($search) {
                    $q->where('ProductRmc', 'LIKE', "%$search%")
                    ->orWhere('IsalesOfferedPrice', 'LIKE', "%$search%")
                    ->orWhere('QuantityRequired', 'LIKE', "%$search%")
                    ->orWhere('IsalesMargin', 'LIKE', "%$search%")
                    ->orWhere('IsalesMarginPercentage', 'LIKE', "%$search%")
                    ->orWhere('Remarks', 'LIKE', "%$search%");
                })
                ->orWhereHas('paymentterms', function ($q) use ($search) {
                    $q->where('Name', 'LIKE', "%$search%");
                });
        })        
        ->when($filterDate, function ($query) use ($filterDate) {
            return $query->where('pricerequestforms.DateRequested', 'LIKE', '%' . $filterDate . '%');
        })
        ->when($filterPRF, function ($query) use ($filterPRF) {
            return $query->where('pricerequestforms.PrfNumber', 'LIKE', '%' . $filterPRF . '%');
        })
        ->when($filterAccount, function ($query) use ($filterAccount) {
            return $query->where('primarySalesPerson.full_name', 'LIKE', '%' . $filterAccount . '%');
        })
        ->when($filterClient, function ($query) use ($filterClient) {
            return $query->where('client.name', 'LIKE', '%' . $filterClient . '%');
        })
        ->when($filterProduct, function ($query) use ($filterProduct) {
            return $query->where('products.code', 'LIKE', '%' . $filterProduct . '%');
        })
        ->when($filterRMC, function ($query) use ($filterRMC) {
            return $query->where('pricerequestproducts.ProductRmc', 'LIKE', '%' . $filterRMC . '%');
        })
        ->when($filterOffered, function ($query) use ($filterOffered) {
            return $query->where('pricerequestproducts.IsalesOfferedPrice', 'LIKE', '%' . $filterOffered . '%');
        })
        ->when($filterShipment, function ($query) use ($filterShipment) {
            return $query->where('pricerequestforms.ShipmentTerm', 'LIKE', '%' . $filterShipment . '%');
        })
        ->when($filterPayment, function ($query) use ($filterPayment) {
            return $query->where('paymentterms.Name', 'LIKE', '%' . $filterPayment . '%'); // Ensure correct column name
        })
        ->when($filterQuantity, function ($query) use ($filterQuantity) {
            return $query->where('pricerequestproducts.QuantityRequired', 'LIKE', '%' . $filterQuantity . '%');
        })
        ->when($filterMargin, function ($query) use ($filterMargin) {
            return $query->where('pricerequestproducts.LsalesMarkupValue', 'LIKE', '%' . $filterMargin . '%');
        })
        ->when($filterPercentMargin, function ($query) use ($filterPercentMargin) {
            return $query->where('pricerequestproducts.LsalesMarkupPercent', 'LIKE', '%' . $filterPercentMargin . '%');
        })
        ->when($filterTotalMargin, function ($query) use ($filterTotalMargin) {
            return $query->where('pricerequestproducts.IsalesMargin', 'LIKE', '%' . $filterTotalMargin . '%');
        })
        ->when($filterAccepted, function ($query) use ($filterAccepted) {
            return $query->where('pricerequestforms.IsAccepted', 'LIKE', '%' . $filterAccepted . '%');
        })
        ->when($filterRemarks, function ($query) use ($filterRemarks) {
            return $query->where('pricerequestforms.Remarks', 'LIKE', '%' . $filterRemarks . '%');
        })
        ->orderBy($sort, $direction);

        // Fetch all data or paginate
        if ($fetchAll) {
            $priceRequests = $query->get();
        } else {
            $priceRequests = $query->paginate($entries);
        }
        // dd($priceRequests);
        $allIds = PriceMonitoring::pluck('id')->unique();

        // Use these optimized queries
        $allDates = PriceMonitoring::whereIn('pricerequestforms.id', $allIds)->pluck('DateRequested')->unique()->sort()->values();
        $allPrf = PriceMonitoring::whereIn('pricerequestforms.id', $allIds)->pluck('PrfNumber')->unique()->sort()->values();
        $allPrimarySalesPersons = User::whereIn('users.user_id', PriceMonitoring::pluck('PrimarySalesPersonId')->unique())->pluck('full_name')->unique()->sort()->values();
        $allClients = Client::whereIn('id', PriceMonitoring::pluck('ClientId')->unique())->pluck('name')->unique();
        $allProducts = Product::whereIn('id', PriceRequestProduct::pluck('ProductId')->unique())->pluck('code')->unique();
        $allRmc = PriceRequestProduct::pluck('ProductRmc')->unique()->sort()->values();
        $allOffered = PriceRequestProduct::pluck('IsalesOfferedPrice')->unique()->sort()->values();
        $allShipment = PriceMonitoring::pluck('ShipmentTerm')->unique()->sort()->values();
        $allPayment = PaymentTerms::pluck('Name')->unique()->sort()->values();
        $allQuantity = PriceRequestProduct::pluck('QuantityRequired')->unique()->sort()->values();
        $allMargin = PriceRequestProduct::pluck('LsalesMarkupValue')->unique()->sort()->values();
        $allPercentMargin = PriceRequestProduct::pluck('LsalesMarkupPercent')->unique()->sort()->values();
        $allTotalMargin = PriceRequestProduct::pluck('IsalesMargin')->unique()->sort()->values();
        $allAccepted = PriceMonitoring::pluck('IsAccepted')->unique()->sort()->values();
        $allRemarks = PriceMonitoring::pluck('Remarks')->unique()->sort()->values();

        return view('reports.price_summary', compact(
            'search', 'priceRequests', 'entries', 'fetchAll', 'sort', 'direction', 'allDates', 'allPrf',
            'allPrimarySalesPersons', 'allClients', 'allProducts', 'allRmc',
            'allOffered', 'allMargin', 'allPercentMargin', 'allShipment',
            'allPayment', 'allQuantity', 'allTotalMargin', 'allAccepted', 'allRemarks', 'from', 'to'
        ));
        
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
    public function exportPriceRequests(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'DateRequested'); // Default sort field
        $direction = $request->get('direction', 'desc'); // Default sort direction

        $from = $request->input('from') ?: now()->startOfMonth()->format('Y-m-d');
        $to = $request->input('to') ?: now()->endOfMonth()->format('Y-m-d');

        // Get filter inputs
        $filterDate = $request->input('filter_date');
        $filterPRF = $request->input('filter_prf');
        $filterAccount = $request->input('filter_account_manager');
        $filterClient = $request->input('filter_client');
        $filterProduct = $request->input('filter_product');
        $filterRMC = $request->input('filter_rmc');
        $filterOffered = $request->input('filter_offered');
        $filterShipment = $request->input('filter_shipment');
        $filterPayment = $request->input('filter_payment');
        $filterQuantity = $request->input('filter_quantity');
        $filterMargin = $request->input('filter_margin');
        $filterPercentMargin = $request->input('filter_percent_margin');
        $filterTotalMargin = $request->input('filter_total_margin');
        $filterAccepted = $request->input('filter_accepted');
        $filterRemarks = $request->input('filter_remarks');

        $validSorts = [
            'DateRequested', 'PrimarySalesPersonId', 'ClientId', 'ProductCode', 'ProductRmc', 'OfferedPrice', 
            'ShipmentTerm', 'PaymentTerm', 'QuantityRequired', 'Margin', 'MarginPercentage', 'TotalMargin', 
            'IsAccepted', 'Remarks'
        ];

        if (!in_array($sort, $validSorts)) {
            $sort = 'DateRequested'; // Default sort field
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc'; // Default sort direction
        }

        $priceRequests = PriceMonitoring::with([
                'primarySalesPerson:user_id,full_name', 
                'client:id,name', 
                'priceRequestProduct:id,ProductId,ProductRmc,IsalesOfferedPrice,QuantityRequired,IsalesMargin,IsalesMarginPercentage', 
                'paymentterms:id,Name',
                'products:ProductId,code'
            ])
            ->leftJoin('pricerequestproducts', 'pricerequestproducts.PriceRequestFormId', '=', 'pricerequestforms.id')
            ->leftJoin('products', 'products.id', '=', 'pricerequestproducts.ProductId')
            ->leftJoin('clientpaymentterms', 'clientpaymentterms.id', '=', 'pricerequestforms.PaymentTermId')
            ->leftJoin('users as primarySalesPerson', 'primarySalesPerson.id', '=', 'pricerequestforms.PrimarySalesPersonId')
            ->leftJoin('clientcompanies as client', 'client.id', '=', 'pricerequestforms.ClientId')
            ->select('pricerequestforms.*', 'pricerequestproducts.*', 'products.code as ProductCode', 'clientpaymentterms.Name as PaymentTermName')
            ->whereBetween('pricerequestforms.DateRequested', [$from, $to]);

        // Apply filters
        if ($filterDate) {
            $priceRequests->where('DateRequested', 'LIKE', "%$filterDate%");
        }

        if ($filterPRF) {
            $priceRequests->where('PrfNumber', 'LIKE', "%$filterPRF%");
        }

        if ($filterAccount) {
            $priceRequests->whereHas('primarySalesPerson', function ($q) use ($filterAccount) {
                $q->where('full_name', 'LIKE', "%$filterAccount%");
            });
        }

        if ($filterClient) {
            $priceRequests->whereHas('client', function ($q) use ($filterClient) {
                $q->where('name', 'LIKE', "%$filterClient%");
            });
        }

        if ($filterProduct) {
            $priceRequests->whereHas('products', function ($q) use ($filterProduct) {
                $q->where('code', 'LIKE', "%$filterProduct%");
            });
        }

        if ($filterRMC) {
            $priceRequests->whereHas('priceRequestProduct', function ($q) use ($filterRMC) {
                $q->where('ProductRmc', 'LIKE', "%$filterRMC%");
            });
        }

        if ($filterOffered) {
            $priceRequests->whereHas('priceRequestProduct', function ($q) use ($filterOffered) {
                $q->where('IsalesOfferedPrice', 'LIKE', "%$filterOffered%");
            });
        }

        if ($filterShipment) {
            $priceRequests->where('ShipmentTerm', 'LIKE', "%$filterShipment%");
        }

        if ($filterPayment) {
            $priceRequests->whereHas('paymentterms', function ($q) use ($filterPayment) {
                $q->where('Name', 'LIKE', "%$filterPayment%");
            });
        }

        if ($filterQuantity) {
            $priceRequests->whereHas('priceRequestProduct', function ($q) use ($filterQuantity) {
                $q->where('QuantityRequired', 'LIKE', "%$filterQuantity%");
            });
        }

        if ($filterMargin) {
            $priceRequests->whereHas('priceRequestProduct', function ($q) use ($filterMargin) {
                $q->where('IsalesMargin', 'LIKE', "%$filterMargin%");
            });
        }

        if ($filterPercentMargin) {
            $priceRequests->whereHas('priceRequestProduct', function ($q) use ($filterPercentMargin) {
                $q->where('IsalesMarginPercentage', 'LIKE', "%$filterPercentMargin%");
            });
        }

        if ($filterTotalMargin) {
            $priceRequests->whereHas('priceRequestProduct', function ($q) use ($filterTotalMargin) {
                $q->where('IsalesMargin', 'LIKE', "%$filterTotalMargin%"); // Ensure this is correct
            });
        }

        if ($filterAccepted) {
            $priceRequests->where('IsAccepted', 'LIKE', "%$filterAccepted%");
        }

        if ($filterRemarks) {
            $priceRequests->where('Remarks', 'LIKE', "%$filterRemarks%");
        }

        // Apply global search
        if ($search) {
            $priceRequests->where(function ($query) use ($search) {
                $query->where('DateRequested', 'LIKE', "%$search%")
                    ->orWhere('PrfNumber', 'LIKE', "%$search%")
                    ->orWhere('ShipmentTerm', 'LIKE', "%$search%")
                    ->orWhereHas('products', function ($q) use ($search) {
                        $q->where('code', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('primarySalesPerson', function ($q) use ($search) {
                        $q->where('full_name', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%");
                    });
            });
        }
        
        $priceRequests->orderBy($sort, $direction);

        $priceRequests = $priceRequests->get();

        $data = $priceRequests->map(function ($item) {
            $totalCost = $item->ProductRmc +
            $item->LsalesDirectLabor +
            $item->LsalesFactoryOverhead +
            $item->LsalesDeliveryCost +
            $item->LsalesFinancingCost +
            $item->LsalesGaeValue +
            $item->OtherCostRequirements +
            $item->LsalesBlendingLoss;

            $totalCost = round($totalCost, 2);
            $markupValue = $item->LsalesMarkupValue;

        
            $markupValue = (float) $markupValue;

            $sellingPrice = $totalCost + $markupValue;

            $formattedSellingPrice = number_format($sellingPrice, 2);

            return [

                'DateRequested' => $item->DateRequested,
                'PrimarySalesPerson' => $item->primarySalesPerson->full_name ?? 'N/A',
                'Client' => $item->client->name ?? 'N/A',
                'ProductCode' => $item->ProductCode,
                'ProductRmc' => $item->ProductRmc ?? 'N/A',
                // 'OfferedPrice' => $item->IsalesOfferedPrice ?? '0',
                'Selling Price' => $formattedSellingPrice,
                'QuantityRequired' => $item->QuantityRequired ?? 'N/A',
                'Margin' => $item->LsalesMarkupValue ?? 'N/A',
                'MarginPercentage' => $item->LsalesMarkupPercent ?? 'N/A',
                // 'TotalMargin' => $item->TotalMargin ?? 'N/A',
                'ShipmentTerm' => $item->ShipmentTerm ?? 'N/A',
                'PaymentTerm' => $item->PaymentTermName ?? 'N/A',
                'IsAccepted' => $item->IsAccepted ? 'YES' : 'NO',
                'Remarks' => $item->Remarks ?? 'N/A',
            ];
        });

        return response()->json($data);
    }

    // Transaction/Activity
    public function transaction_summary(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'client'); 
        $direction = $request->get('direction', 'desc');
        $role = auth()->user()->role;
        $fetchAll = $request->input('fetch_all', false);
        $entries = $request->input('number_of_entries', 10);

        // Use provided 'from' and 'to' dates or default to the current month if not provided
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->input('to', now()->endOfMonth()->format('Y-m-d'));

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
        $validSorts = ['type', 'status', 'client', 'transaction_number', 'date_created'];
        $sort = in_array($sort, $validSorts) ? $sort : 'client';
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'desc';

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
                ->whereBetween('customerrequirements.DateCreated', [$from, $to])
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
                })
                ->when(optional($role)->type, function($q) use ($role) {
                    if ($role->type == "IS") {
                        $q->where('CrrNumber', 'LIKE', 'CRR-IS%');  
                    } elseif ($role->type == "LS") {
                        $q->where('CrrNumber', 'LIKE', 'CRR-LS%');  
                    }
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
                ->whereBetween('samplerequests.created_at', [$from, $to])
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
                })
                ->when(optional($role)->type, function($q) use ($role) {
                    if ($role->type == "IS") {
                        $q->where('SrfNumber', 'LIKE', 'SRF-IS%');  
                    } elseif ($role->type == "LS") {
                        $q->where('SrfNumber', 'LIKE', 'SRF-LS%');  
                    }
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
                ->whereBetween('requestproductevaluations.CreatedDate', [$from, $to])
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
                })
                ->when(optional($role)->type, function($q) use ($role) {
                    if ($role->type == "IS") {
                        $q->where('RpeNumber', 'LIKE', 'RPE-IS%');  
                    } elseif ($role->type == "LS") {
                        $q->where('RpeNumber', 'LIKE', 'RPE-LS%');  
                    }
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
                ->whereBetween('pricerequestforms.DateRequested', [$from, $to])
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
                    return $query->where('pricerequestforms.DateRequested', 'LIKE', '%' . $filterDateCreated . '%');
                })
                ->when($filterDueDate, function ($query) use ($filterDueDate) {
                    return $query->where('pricerequestforms.ValidityDate', 'LIKE', '%' . $filterDueDate . '%');
                })
                ->when($filterStatus, function ($query) use ($filterStatus) {
                    return $query->where('pricerequestforms.Status', 'LIKE', '%' . $filterStatus . '%');
                })
                ->when($filterProgress, function ($query) use ($filterProgress) {
                    return $query->where('srfprogresses.name', 'LIKE', '%' . $filterProgress . '%');
                })
                ->when(optional($role)->type, function($q) use ($role) {
                    if ($role->type == "IS") {
                        $q->where('PrfNumber', 'LIKE', 'PRF-IS%');  
                    } elseif ($role->type == "LS") {
                        $q->where('PrfNumber', 'LIKE', 'PRF-LS%');  
                    }
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
                ->whereBetween('activities.created_at', [$from, $to])
                ->where(function ($query) use ($search) {
                    $query->where('activities.ActivityNumber', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                        ->orWhere('activities.Title', 'LIKE', '%' . $search . '%')
                        ->orWhere('activities.Response', 'LIKE', '%' . $search . '%')
                        ->orWhere('activities.Status', 'LIKE', '%' . $search . '%')
                        ->orWhere(DB::raw("'Activity'"), 'LIKE', '%' . $search . '%');
                })
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
                })
                ->when(optional($role)->type, function($q) use ($role) {
                    if ($role->type == "IS") {
                        $q->where('ActivityNumber', 'LIKE', 'ACT-IS%');  
                    } elseif ($role->type == "LS") {
                        $q->where('ActivityNumber', 'LIKE', 'ACT-LS%');  
                    }
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
        $role = auth()->user()->role;
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
                DB::raw("CASE 
                            WHEN customerrequirements.Status = 10 THEN 'Open' 
                            WHEN customerrequirements.Status = 20 THEN 'Closed'
                            ELSE 'Unknown'
                        END as status"
                ),
                'srfprogresses.name as progress'
            )
            ->leftJoin('users', 'customerrequirements.PrimarySalesPersonId', '=', 'users.user_id')
            ->leftJoin('clientcompanies', 'customerrequirements.ClientId', '=', 'clientcompanies.id')
            ->leftJoin('srfprogresses', 'customerrequirements.Progress', '=', 'srfprogresses.id')
            ->whereBetween('customerrequirements.DateCreated', [$from, $to])
            ->where(function ($query) use ($search) {
                $query->where('customerrequirements.CrrNumber', 'LIKE', '%' . $search . '%')
                    ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                    ->orWhere('customerrequirements.DetailsOfRequirement', 'LIKE', '%' . $search . '%')
                    ->orWhere('customerrequirements.Recommendation', 'LIKE', '%' . $search . '%')
                    ->orWhere(DB::raw("CASE 
                        WHEN customerrequirements.Status = 10 THEN 'Open' 
                        WHEN customerrequirements.Status = 20 THEN 'Closed'
                        ELSE 'Unknown'
                    END"), 'LIKE', '%' . $search . '%')
                    ->orWhere('srfprogresses.name', 'LIKE', '%' . $search . '%')
                    ->orWhere(DB::raw("'Customer Requirement'"), 'LIKE', '%' . $search . '%');
            })
            ->when($filterType, fn($query) => $query->where(DB::raw("'Customer Requirement'"), '=', $filterType))
            ->when($filterTransactionNumber, fn($query) => $query->where('customerrequirements.CrrNumber', 'LIKE', '%' . $filterTransactionNumber . '%'))
            ->when($filterBDE, fn($query) => $query->where('users.full_name', 'LIKE', '%' . $filterBDE . '%'))
            ->when($filterClient, fn($query) => $query->where('clientcompanies.Name', 'LIKE', '%' . $filterClient . '%'))
            ->when($filterDateCreated, fn($query) => $query->whereDate('customerrequirements.DateCreated', '=', $filterDateCreated))
            ->when($filterDueDate, fn($query) => $query->whereDate('customerrequirements.DueDate', '=', $filterDueDate))
            ->when($filterStatus, fn($query) => $query->where(DB::raw("CASE 
                WHEN customerrequirements.Status = 10 THEN 'Open' 
                WHEN customerrequirements.Status = 20 THEN 'Closed'
                ELSE 'Unknown'
            END"), '=', $filterStatus))
            ->when($filterProgress, fn($query) => $query->where('srfprogresses.name', 'LIKE', '%' . $filterProgress . '%'))
            ->when(optional($role)->type, function($q) use ($role) {
                if ($role->type == "IS") {
                    $q->where('CrrNumber', 'LIKE', 'CRR-IS%');  
                } elseif ($role->type == "LS") {
                    $q->where('CrrNumber', 'LIKE', 'CRR-LS%');  
                }
            });
        

        $srf_data = SampleRequest::select(
                DB::raw("'Sample Request' as type"),
                'samplerequests.SrfNumber as transaction_number', 
                'users.full_name as bde',
                'clientcompanies.Name as client', 
                DB::raw("DATE_FORMAT(samplerequests.created_at, '%b. %d, %Y') as date_created"),
                DB::raw("DATE_FORMAT(samplerequests.DateRequired, '%b. %d, %Y') as due_date"),
                'samplerequests.InternalRemarks as details', 
                'samplerequests.Disposition as result',  
                DB::raw("CASE 
                            WHEN samplerequests.Status = 10 THEN 'Open' 
                            WHEN samplerequests.Status = 20 THEN 'Closed'
                            ELSE 'Unknown'
                        END as status"
                ),
                'srfprogresses.name as progress'
            )
            ->leftJoin('users', 'samplerequests.PrimarySalesPersonId', '=', 'users.user_id')
            ->leftJoin('clientcompanies', 'samplerequests.ClientId', '=', 'clientcompanies.id')
            ->leftJoin('srfprogresses', 'samplerequests.Progress', '=', 'srfprogresses.id')
            ->whereBetween('samplerequests.created_at', [$from, $to])
            ->where(function ($query) use ($search) {
                $query->where('samplerequests.SrfNumber', 'LIKE', '%' . $search . '%')
                    ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                    ->orWhere('samplerequests.InternalRemarks', 'LIKE', '%' . $search . '%')
                    ->orWhere('samplerequests.Disposition', 'LIKE', '%' . $search . '%')
                    ->orWhere(DB::raw("CASE 
                        WHEN samplerequests.Status = 10 THEN 'Open' 
                        WHEN samplerequests.Status = 20 THEN 'Closed'
                        ELSE 'Unknown'
                    END"), 'LIKE', '%' . $search . '%')
                    ->orWhere('srfprogresses.name', 'LIKE', '%' . $search . '%')
                    ->orWhere(DB::raw("'Sample Request'"), 'LIKE', '%' . $search . '%');
            })
            ->when($filterType, fn($query) => $query->where(DB::raw("'Sample Request'"), '=', $filterType))
            ->when($filterTransactionNumber, fn($query) => $query->where('samplerequests.SrfNumber', 'LIKE', '%' . $filterTransactionNumber . '%'))
            ->when($filterBDE, fn($query) => $query->where('users.full_name', 'LIKE', '%' . $filterBDE . '%'))
            ->when($filterClient, fn($query) => $query->where('clientcompanies.Name', 'LIKE', '%' . $filterClient . '%'))
            ->when($filterDateCreated, fn($query) => $query->whereDate('samplerequests.created_at', '=', $filterDateCreated))
            ->when($filterDueDate, fn($query) => $query->whereDate('samplerequests.DateRequired', '=', $filterDueDate))
            ->when($filterStatus, fn($query) => $query->where(DB::raw("CASE 
                WHEN samplerequests.Status = 10 THEN 'Open' 
                WHEN samplerequests.Status = 20 THEN 'Closed'
                ELSE 'Unknown'
            END"), '=', $filterStatus))
            ->when($filterProgress, fn($query) => $query->where('srfprogresses.name', 'LIKE', '%' . $filterProgress . '%'))
            ->when(optional($role)->type, function($q) use ($role) {
                if ($role->type == "IS") {
                    $q->where('SrfNumber', 'LIKE', 'SRF-IS%');  
                } elseif ($role->type == "LS") {
                    $q->where('SrfNumber', 'LIKE', 'SRF-LS%');  
                }
            });
            

        $rpe_data = RequestProductEvaluation::select(
                DB::raw("'Request Product Evaluation' as type"),
                'requestproductevaluations.RpeNumber as transaction_number', 
                'users.full_name as bde',
                'clientcompanies.Name as client', 
                DB::raw("DATE_FORMAT(requestproductevaluations.CreatedDate, '%b. %d, %Y') as date_created"),
                DB::raw("DATE_FORMAT(requestproductevaluations.DueDate, '%b. %d, %Y') as due_date"),
                'requestproductevaluations.ObjectiveForRpeProject as details', 
                'requestproductevaluations.RpeResult as result',
                DB::raw("CASE 
                            WHEN requestproductevaluations.Status = 10 THEN 'Open' 
                            WHEN requestproductevaluations.Status = 20 THEN 'Closed'
                            ELSE 'Unknown'
                        END as status"
                ),
                'srfprogresses.name as progress'
            )
            ->leftJoin('users', 'requestproductevaluations.PrimarySalesPersonId', '=', 'users.user_id')
            ->leftJoin('clientcompanies', 'requestproductevaluations.ClientId', '=', 'clientcompanies.id')
            ->leftJoin('srfprogresses', 'requestproductevaluations.Progress', '=', 'srfprogresses.id')
            ->whereBetween('requestproductevaluations.CreatedDate', [$from, $to])
            ->where(function ($query) use ($search) {
                $query->where('requestproductevaluations.RpeNumber', 'LIKE', '%' . $search . '%')
                    ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                    ->orWhere('requestproductevaluations.ObjectiveForRpeProject', 'LIKE', '%' . $search . '%')
                    ->orWhere('requestproductevaluations.RpeResult', 'LIKE', '%' . $search . '%')
                    ->orWhere(DB::raw("CASE 
                        WHEN requestproductevaluations.Status = 10 THEN 'Open' 
                        WHEN requestproductevaluations.Status = 20 THEN 'Closed'
                        ELSE 'Unknown'
                    END"), 'LIKE', '%' . $search . '%')
                    ->orWhere('srfprogresses.name', 'LIKE', '%' . $search . '%')
                    ->orWhere(DB::raw("'Request Product Evaluation'"), 'LIKE', '%' . $search . '%');
            })
            ->when($filterType, fn($query) => $query->where(DB::raw("'Request Product Evaluation'"), '=', $filterType))
            ->when($filterTransactionNumber, fn($query) => $query->where('requestproductevaluations.RpeNumber', 'LIKE', '%' . $filterTransactionNumber . '%'))
            ->when($filterBDE, fn($query) => $query->where('users.full_name', 'LIKE', '%' . $filterBDE . '%'))
            ->when($filterClient, fn($query) => $query->where('clientcompanies.Name', 'LIKE', '%' . $filterClient . '%'))
            ->when($filterDateCreated, fn($query) => $query->whereDate('requestproductevaluations.CreatedDate', '=', $filterDateCreated))
            ->when($filterDueDate, fn($query) => $query->whereDate('requestproductevaluations.DueDate', '=', $filterDueDate))
            ->when($filterStatus, fn($query) => $query->where(DB::raw("CASE 
                WHEN requestproductevaluations.Status = 10 THEN 'Open' 
                WHEN requestproductevaluations.Status = 20 THEN 'Closed'
                ELSE 'Unknown'
            END"), '=', $filterStatus))
            ->when($filterProgress, fn($query) => $query->where('srfprogresses.name', 'LIKE', '%' . $filterProgress . '%'))
            ->when(optional($role)->type, function($q) use ($role) {
                if ($role->type == "IS") {
                    $q->where('RpeNumber', 'LIKE', 'RPE-IS%');  
                } elseif ($role->type == "LS") {
                    $q->where('RpeNumber', 'LIKE', 'RPE-LS%');  
                }
            });
            

        $price_data = PriceMonitoring::select(
                DB::raw("'Price Monitoring' as type"),
                'pricerequestforms.PrfNumber as transaction_number', 
                'users.full_name as bde',
                'clientcompanies.Name as client', 
                DB::raw("DATE_FORMAT(pricerequestforms.DateRequested, '%b. %d, %Y') as date_created"),
                DB::raw("DATE_FORMAT(pricerequestforms.ValidityDate, '%b. %d, %Y') as due_date"),
                'pricerequestforms.Remarks as details', 
                'pricerequestforms.DispositionRemarks as result',  
                DB::raw("CASE 
                            WHEN pricerequestforms.Status = 10 THEN 'Open' 
                            WHEN pricerequestforms.Status = 20 THEN 'Closed'
                            ELSE 'Unknown'
                        END as status"
                ),
                'srfprogresses.name as progress'
            )
            ->leftJoin('users', 'pricerequestforms.PrimarySalesPersonId', '=', 'users.user_id')
            ->leftJoin('clientcompanies', 'pricerequestforms.ClientId', '=', 'clientcompanies.id')
            ->leftJoin('srfprogresses', 'pricerequestforms.Progress', '=', 'srfprogresses.id')
            ->whereBetween('pricerequestforms.DateRequested', [$from, $to])
            ->where(function ($query) use ($search) {
                $query->where('pricerequestforms.PrfNumber', 'LIKE', '%' . $search . '%')
                    ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                    ->orWhere('pricerequestforms.Remarks', 'LIKE', '%' . $search . '%')
                    ->orWhere('pricerequestforms.DispositionRemarks', 'LIKE', '%' . $search . '%')
                    ->orWhere(DB::raw("CASE 
                        WHEN pricerequestforms.Status = 10 THEN 'Open' 
                        WHEN pricerequestforms.Status = 20 THEN 'Closed'
                        ELSE 'Unknown'
                    END"), 'LIKE', '%' . $search . '%')
                    ->orWhere('srfprogresses.name', 'LIKE', '%' . $search . '%')
                    ->orWhere(DB::raw("'Price Monitoring'"), 'LIKE', '%' . $search . '%');
            })
            ->when($filterType, fn($query) => $query->where(DB::raw("'Price Monitoring'"), '=', $filterType))
            ->when($filterTransactionNumber, fn($query) => $query->where('pricerequestforms.PmNumber', 'LIKE', '%' . $filterTransactionNumber . '%'))
            ->when($filterBDE, fn($query) => $query->where('users.full_name', 'LIKE', '%' . $filterBDE . '%'))
            ->when($filterClient, fn($query) => $query->where('clientcompanies.Name', 'LIKE', '%' . $filterClient . '%'))
            ->when($filterDateCreated, fn($query) => $query->whereDate('pricerequestforms.DateRequested', '=', $filterDateCreated))
            ->when($filterDueDate, fn($query) => $query->whereDate('pricerequestforms.ValidityDate', '=', $filterDueDate))
            ->when($filterStatus, fn($query) => $query->where(DB::raw("CASE 
                WHEN pricerequestforms.Status = 10 THEN 'Open' 
                WHEN pricerequestforms.Status = 20 THEN 'Closed'
                ELSE 'Unknown'
            END"), '=', $filterStatus))
            ->when($filterProgress, fn($query) => $query->where('srfprogresses.name', 'LIKE', '%' . $filterProgress . '%'))
            ->when(optional($role)->type, function($q) use ($role) {
                if ($role->type == "IS") {
                    $q->where('Prfnumber', 'LIKE', 'PRF-IS%');  
                } elseif ($role->type == "LS") {
                    $q->where('Prfnumber', 'LIKE', 'PRF-LS%');  
                }
            });
            

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
                        ELSE CAST(activities.Status AS CHAR)
                    END as status
                "),
                DB::raw("
                    CASE 
                        WHEN activities.Status = 10 THEN 'Open' 
                        WHEN activities.Status = 20 THEN 'Closed'
                        ELSE IFNULL(srfprogresses.name, 'Unknown')
                    END as progress
                ")  
            )
            ->leftJoin('users', 'activities.PrimaryResponsibleUserId', '=', 'users.user_id')
            ->leftJoin('clientcompanies', 'activities.ClientId', '=', 'clientcompanies.id')
            ->leftJoin('srfprogresses', 'activities.Status', '=', 'srfprogresses.id')  // Correct join condition
            ->whereBetween('activities.created_at', [$from, $to])
            ->where(function ($query) use ($search) {
                $query->where('activities.ActivityNumber', 'LIKE', '%' . $search . '%')
                    ->orWhere('users.full_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('clientcompanies.Name', 'LIKE', '%' . $search . '%')
                    ->orWhere('activities.Title', 'LIKE', '%' . $search . '%')
                    ->orWhere('activities.Response', 'LIKE', '%' . $search . '%')
                    ->orWhere(DB::raw("
                        CASE 
                            WHEN activities.Status = 10 THEN 'Open' 
                            WHEN activities.Status = 20 THEN 'Closed'
                            ELSE CAST(activities.Status AS CHAR)
                        END
                    "), 'LIKE', '%' . $search . '%')
                    ->orWhere(DB::raw("
                        CASE 
                            WHEN activities.Status = 10 THEN 'Open' 
                            WHEN activities.Status = 20 THEN 'Closed'
                            ELSE IFNULL(srfprogresses.name, 'Unknown')
                        END
                    "), 'LIKE', '%' . $search . '%')
                    ->orWhere(DB::raw("'Activity'"), 'LIKE', '%' . $search . '%');
            })
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
                return $query->whereDate('activities.created_at', '=', $filterDateCreated);
            })
            ->when($filterDueDate, function ($query) use ($filterDueDate) {
                return $query->whereDate('activities.ScheduleTo', '=', $filterDueDate);
            })
            ->when($filterStatus, function ($query) use ($filterStatus) {
                return $query->where('activities.Status', '=', $filterStatus);
            })
            
            ->when($filterProgress, function ($query) use ($filterProgress) {
                return $query->where(DB::raw("
                    CASE 
                        WHEN activities.Status = 10 THEN 'Open' 
                        WHEN activities.Status = 20 THEN 'Closed'
                        ELSE IFNULL(srfprogresses.name, 'Unknown')
                    END
                "), '=', $filterProgress);
            });



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

    public function sample_summary(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'SampleRequestId');
        $direction = $request->get('direction', 'desc');
        $fetchAll = $request->input('fetch_all', false);
        $entries = $request->input('number_of_entries', 10);

        $from = $request->input('from') ?: now()->startOfMonth()->format('Y-m-d');
        $to = $request->input('to') ?: now()->endOfMonth()->format('Y-m-d');

        $query = SampleRequestProduct::with(['sampleRequest', 'uom'])
            ->whereHas('sampleRequest', function($q) use ($from, $to) {
                $q->whereBetween('DateDispatched', [$from, $to]);
            })
            ->where(function ($query) use ($search) {
                $query->where('ProductCode', 'LIKE', "%$search%")
                    ->orWhere('Quantity', 'LIKE', "%$search%")
                    ->orWhere('ProductDescription', 'LIKE', "%$search%")
                    ->orWhere('NumberOfPackages', 'LIKE', "%$search%")
                    ->orWhere('ProductIndex', 'LIKE', "%$search%")
                    ->orWhereHas('sampleRequest', function ($q) use ($search) {
                        $q->where('DateSampleReceived', 'LIKE', "%$search%")
                            ->orWhere('DateDispatched', 'LIKE', "%$search%")
                            ->orWhere('SrfNumber', 'LIKE', "%$search%")
                            ->orWhere('Courier', 'LIKE', "%$search%")
                            ->orWhere('AwbNumber', 'LIKE', "%$search%")
                            ->orWhere('Eta', 'LIKE', "%$search%")
                            ->orWhere('CourierCost', 'LIKE', "%$search%")
                            ->orWhere('SrfType', 'LIKE', "%$search%")
                            ->orWhere('RefCode', 'LIKE', "%$search%")
                            ->orWhere('Reason', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('sampleRequest.client', function ($q) use ($search) {
                        $q->where('Name', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('sampleRequest.clientContact', function ($q) use ($search) {
                        $q->where('ContactName', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('sampleRequest.clientAddress', function ($q) use ($search) {
                        $q->where('Address', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('sampleRequest.primarySalesPerson', function ($q) use ($search) {
                        $q->where('full_name', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('sampleRequest.dispatchBy', function ($q) use ($search) {
                        $q->where('full_name', 'LIKE', "%$search%");
                    });
            })  
            ->orderBy($sort, $direction);
        
            if ($fetchAll) {
                $sample_dispatch = $query->get();
            } else {
                $sample_dispatch = $query->paginate($entries);
            }

        return view('reports.sample_summary', compact(
            'search', 'entries', 'fetchAll', 'sort', 'direction', 'from', 'to', 'sample_dispatch',
        ));
    }

    public function exportSampleDispatch(Request $request)
    {
        $from = $request->input('from') ?: now()->startOfMonth()->format('Y-m-d');
        $to = $request->input('to') ?: now()->endOfMonth()->format('Y-m-d');
        
        return Excel::download(new SampleDispatchReportExport($from, $to), 'Sample Dispatch.xlsx');
    }
}
