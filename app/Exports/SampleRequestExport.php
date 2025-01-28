<?php

namespace App\Exports;

use App\CustomerRequirement;
use App\SampleRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SampleRequestExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $open;
    protected $close;

    public function __construct($open, $close)
    {
        $this->open = $open;
        $this->close = $close;
    }

    public function collection()
    {
        $openStatus = $this->open;
        $closeStatus = $this->close;

        if((auth()->user()->role->type == "IS") || (auth()->user()->role->type == "CS"))
        {
            return SampleRequest::with('requestProducts')
                ->when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
                    $query->whereIn('Status', [$openStatus, $closeStatus]);
                })
                ->when($openStatus != null && $closeStatus == null, function($query)use($openStatus) {
                    $query->where('Status', $openStatus);
                })
                ->when($closeStatus != null && $openStatus == null, function($query)use($closeStatus) {
                    $query->where('Status', $closeStatus);
                })
                ->latest()
                ->get();
        }

        if((auth()->user()->role->type == "LS") || (auth()->user()->role->type == "CS"))
        {
            return SampleRequest::with('requestProducts')
                ->when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
                    $query->whereIn('Status', [$openStatus, $closeStatus]);
                })
                ->when($openStatus != null && $closeStatus == null, function($query)use($openStatus) {
                    $query->where('Status', $openStatus);
                })
                ->when($closeStatus != null && $openStatus == null, function($query)use($closeStatus) {
                    $query->where('Status', $closeStatus);
                })
                ->latest()
                ->get();
        }

    }

    public function headings(): array
    {
        if((auth()->user()->role->type == "IS") || (auth()->user()->role->type == "CS"))
        {
            return [
                'SRF #',
                'Date Requested',
                'Date Required',
                'Ref Code',
                'Type',
                'Client Name',
                'Region',
                'Country',
                'Primary Sales Person',
                'Number Of Packages',
                'Quantity',
                'Product Code',
                'Product Label',
                'Application',
                'Description',
                'RPE No.',
                'CRR No.',
                'Date Sample Received',
                'Date Dispatched',
                'Status',
                'Progress'
            ];
        }

        if((auth()->user()->role->type == "LS") || (auth()->user()->role->type == "CS"))
        {
            return [
                'SRF #',
                'Date Requested',
                'Date Required',
                'Client Name',
                'Application',
                'Primary Sales Person',
                'Status',
                'Progress'
            ];
        }
    }


    public function map($row): array
    {
        $primarySales = "";
        if ($row->primarySalesPerson)
        {
            $primarySales = $row->primarySalesPerson->full_name;
        }
        elseif($row->primarySalesById)
        {
            $primarySales = $row->primarySalesById->full_name;
        }

        $status = "";
        if ($row->Status == 10)
        {
            $status = "Open";
        }
        elseif($row->Status == 30)
        {
            $status = "Closed";
        }
        elseif($row->Status == 50)
        {
            $status = "Cancelled";
        }

        $RefCode = "";
        if ($row->RefCode == 1)
        {
            $RefCode = "RND";
        }
        elseif($row->RefCode == 2)
        {
            $RefCode = "QCD";
        }

        $SrfType = "";
        if ($row->SrfType == 1)
        {
            $SrfType = "Regular";
        }
        elseif($row->SrfType == 2)
        {
            $SrfType = "PSS";
        }
        elseif($row->SrfType == 3)
        {
            $SrfType = "CSS";
        }

        $Status = "";
        if ($row->Status == 10)
        {
            $Status = "Open";
        }
        elseif($row->Status == 30)
        {
            $Status = "Closed";
        }
       

        // $productApplications = $row->requestProducts->map(function($product) {
        //     return optional($product->productApplicationsId)->Name;
        // })->implode(', ');

        // $productPackages = $row->requestProducts->map(function($product) {
        //     return ($product->NumberOfPackages);
        // })->implode(', ');

        // $productQuantity = $row->requestProducts->map(function($product) {
        //     return ($product->Quantity);
        // })->implode(', ');

        // $productProductCode = $row->requestProducts->map(function($product) {
        //     return ($product->ProductCode);
        // })->implode(', ');

        // $productLabel = $row->requestProducts->map(function($product) {
        //     return ($product->Label);
        // })->implode(', ');

        // $productDescription = $row->requestProducts->map(function($product) {
        //     return ($product->ProductDescription);
        // })->implode(', ');

        // $productRPE = $row->requestProducts->map(function($product) {
        //     return ($product->RpeNumber);
        // })->implode(', ');

        // $productCRR = $row->requestProducts->map(function($product) {
        //     return ($product->CrrNumber);
        // })->implode(', ');

        // $DateSampleReceived = $row->requestProducts->map(function($product) {
        //     return ($product->DateSampleReceived ?? "NA");
        // })->implode(', ');

        // $DateDispatched = $row->requestProducts->map(function($product) {
        //     return ($product->DateDispatched ?? "NA");
        // })->implode(', ');

        if((auth()->user()->role->type == "IS" || (auth()->user()->role->type == "CS")))
        {
            $productRows = [];

            foreach ($row->requestProducts as $product) {
                $productRows[] = [
                    $row->SrfNumber,
                    $row->DateRequested,
                    $row->DateRequired,
                    $RefCode,
                    $SrfType,
                    optional($row->client)->Name,
                    optional(optional($row->client)->clientregion)->Name,
                    optional(optional($row->client)->clientcountry)->Name,
                    $primarySales,
                    $product->NumberOfPackages,
                    $product->Quantity,
                    $product->ProductCode,
                    $product->Label,
                    optional($product->productApplicationsId)->Name,
                    $product->ProductDescription,
                    $product->RpeNumber,
                    $product->CrrNumber,
                    $product->DateSampleReceived ?? "NA",
                    $row->DateDispatched ?? "NA",
                    $status,
                    optional($row->progressStatus)->name,
                ];
            }
    
            return $productRows;
            // return [
            //     $row->SrfNumber,
            //     $row->DateRequested,
            //     $row->DateRequired,
            //     $RefCode,
            //     $SrfType,
            //     optional($row->client)->Name,
            //     optional(optional($row->client)->clientregion)->Name,
            //     optional(optional($row->client)->clientcountry)->Name,
            //     $primarySales,
            //     $productPackages,
            //     $productQuantity,
            //     $productProductCode,
            //     $productLabel,
            //     $productApplications,
            //     $productDescription,
            //     $productRPE,
            //     $productCRR,
            //     $DateSampleReceived,
            //     $DateDispatched,
            //     $Status,
            //     optional($row->progressStatus)->Name,
            // ];
        }

        if((auth()->user()->role->type == "LS") || (auth()->user()->role->type == "CS"))
        {
            $productRows = [];

            foreach ($row->requestProducts as $product) {
                $productRows[] = [
                    $row->SrfNumber,
                    $row->DateRequested,
                    $row->DateRequired,
                    optional($row->client)->Name,
                    optional($product->productApplicationsId)->Name,
                    $primarySales,
                    $status,
                    optional($row->progressStatus)->name,
                ];
            }
    
            return $productRows;
            // return [
            //     $row->SrfNumber,
            //     $row->DateRequested,
            //     $row->DateRequired,
            //     optional($row->client)->Name,
            //     $productApplications,
            //     $Status,
            //     optional($row->progressStatus)->Name,
            // ];
        }
    }
}
