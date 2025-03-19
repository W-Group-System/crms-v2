<?php

namespace App\Exports;

use App\CustomerRequirement;
use App\RequestProductEvaluation;
use App\SampleRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class ProductEvaluationExport implements FromCollection, WithHeadings, WithMapping
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
        $role = auth()->user()->role->type;

        return RequestProductEvaluation::with(['client', 'product_application'])
            ->when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
                $query->whereIn('Status', [$openStatus, $closeStatus]);
            })
            ->when($openStatus != null && $closeStatus == null, function($query)use($openStatus) {
                $query->where('Status', $openStatus);
            })
            ->when($closeStatus != null && $openStatus == null, function($query)use($closeStatus) {
                $query->where('Status', $closeStatus);
            })
            ->when($role,function($q)use($role) {
                if ($role == 'IS')
                {
                    $q->where('RpeNumber', 'LIKE', '%RPE-IS%');
                }
                elseif($role == 'LS')
                {
                    $q->where('RpeNumber', 'LIKE', '%RPE-LS%');
                }
            })
            ->orderBy('RpeNumber', 'desc')
            ->get();

        // if(auth()->user()->role->type == "LS")
        // {
        //     return SampleRequest::with(['client', 'product_application'])
        //         ->when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
        //             $query->whereIn('Status', [$openStatus, $closeStatus]);
        //         })
        //         ->when($openStatus != null && $closeStatus == null, function($query)use($openStatus) {
        //             $query->where('Status', $openStatus);
        //         })
        //         ->when($closeStatus != null && $openStatus == null, function($query)use($closeStatus) {
        //             $query->where('Status', $closeStatus);
        //         })
        //         ->latest()
        //         ->get();
        // }

    }

    public function headings(): array
    {
        if(auth()->user()->role->type == "IS")
        {
            return [
                'RPE #',
                'Date Created',
                'Due Date',
                'Client Name',
                'Region',
                'Country',
                'Primary Sales Person',
                'Project Name',
                'Application',
                'Sample Name',
                'Manufacturer',
                'Date Completed',
                'Leadtime',
                'Delayed',
                'RPE Recommendation',
                'Status',
                'Progress',
            ];
        }

        if(auth()->user()->role->type == "LS")
        {
            return [
                'RPE #',
                'Date Created',
                'Due Date',
                'Client Name',
                'Application',
                'RPE Recommendation',
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
        elseif($row->primarySalesPersonById)
        {
            $primarySales = $row->primarySalesPersonById->full_name;
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
        elseif($row->Status == 50)
        {
            $Status = "Cancelled";
        }
       

        $leadtime = "N/A";
        if ($row->DateReceived && $row->DueDate) {
            $dateReceived = Carbon::parse($row->DateReceived);
            $dueDate = Carbon::parse($row->DueDate);
            $leadtime = $dateReceived->diffInDays($dueDate) . ' days';
        }

        $delay = "N/A";
        if ($row->DueDate) {
            $dueDate = Carbon::parse($row->DueDate);
            $dateCompleted = $row->DateCompleted ? Carbon::parse($row->DateCompleted) : Carbon::now();
            if (is_null($row->DateCompleted) && Carbon::now()->lte($dueDate)) {
                $delay = '0 days'; 
            } else {
                $delay = $dueDate->diffInDays($dateCompleted, false) . ' days';
            }
        }

        if(auth()->user()->role->type == "IS")
        {
            return [
                $row->RpeNumber,
                $row->CreatedDate ?? $row->created_at,
                $row->DueDate,
                optional($row->client)->Name,
                optional(optional($row->client)->clientregion)->Name,
                optional(optional($row->client)->clientcountry)->Name,
                $primarySales,
                optional($row->ProjectName)->Name,
                optional($row->product_application)->Name,
                $row->SampleName,
                $row->Manufacturer,
                $row->DateCompleted,
                $leadtime,
                $delay,
                $row->RpeResult,
                $Status,
                optional($row->progressStatus)->name,
            ];
        }

        if(auth()->user()->role->type == "LS")
        {
            return [
                $row->RpeNumber,
                $row->CreatedDate ?? $row->created_at,
                $row->DueDate,
                optional($row->client)->Name,
                optional($row->product_application)->Name,
                $row->RpeResult,
                $Status,
                optional($row->progressStatus)->name,
            ];
        }
    }
}
