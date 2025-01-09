<?php

namespace App\Exports;

use App\CrrNature;
use App\CustomerRequirement;
use DateTime;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerRequirementExport implements FromCollection, WithHeadings, WithMapping
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

        if(auth()->user()->role->type == "IS")
        {
            return CustomerRequirement::with('crrNature')->select('id','CrrNumber', 'DateCreated', 'DueDate', 'ClientId', 'ApplicationId', 'Competitor', 'PrimarySalesPersonId', 'DetailsOfRequirement', 'Recommendation', 'DateReceived', 'Status', 'Progress')
                ->when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
                    $query->whereIn('Status', [$openStatus, $closeStatus]);
                })
                ->when($openStatus != null && $closeStatus == null, function($query)use($openStatus) {
                    $query->where('Status', $openStatus);
                })
                ->when($closeStatus != null && $openStatus == null, function($query)use($closeStatus) {
                    $query->where('Status', $closeStatus);
                })
                ->where('CrrNumber','LIKE','%CRR-IS%')
                ->latest()
                ->get();
        }

        if(auth()->user()->role->type == "LS")
        {
            return CustomerRequirement::with('crrNature')->select('id', 'CrrNumber', 'DateCreated', 'DueDate', 'ClientId', 'ApplicationId', 'Recommendation', 'Status', 'Progress')
                ->when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
                    $query->whereIn('Status', [$openStatus, $closeStatus]);
                })
                ->when($openStatus != null && $closeStatus == null, function($query)use($openStatus) {
                    $query->where('Status', $openStatus);
                })
                ->when($closeStatus != null && $openStatus == null, function($query)use($closeStatus) {
                    $query->where('Status', $closeStatus);
                })
                ->where('CrrNumber','LIKE','%CRR-LS%')
                ->latest()
                ->get();
        }

    }

    public function headings(): array
    {
        if(auth()->user()->role->type == "IS")
        {
            return [
                'CRR #',
                'DateCreated',
                'Due Date',
                'Client Name',
                'Region',
                'Country',
                'Application',
                'Competitor',
                'Primary Sales Person',
                'Details of Requirement',
                'Recommendation',
                'DateReceived',
                'Days Late',
                'Nature of Request',
                'Status',
                'Progress'
            ];
        }

        if(auth()->user()->role->type == "LS")
        {
            return [
                'CRR #',
                'DateCreated',
                'Due Date',
                'Client Name',
                'Application',
                'Recommendation',
                'Status',
                'Progress'
            ];
        }
    }


    public function map($row): array
    {
        $primarySales = "";
        if ($row->primarySales)
        {
            $primarySales = $row->primarySales->full_name;
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
        
        $crr_nature_array = [];
        
        foreach($row->crrNature as $crrNature)
        {
            $crr_nature_array[] = optional($crrNature->natureOfRequest)->Name;
        }

        $today = new DateTime();
        $due_date = new DateTime($row->DueDate);
        $diff = $due_date->diff($today);

        $days_late = 0;
        $s = "";
        if ($today > $due_date) 
        {
            $days_late = $diff->d;
            $s = $days_late > 1 ? 's' : '';
        } 
        
        if(auth()->user()->role->type == "IS")
        {
            return [
                $row->CrrNumber,
                $row->DateCreated,
                $row->DueDate,
                optional($row->client)->Name,
                optional($row->client->clientregion)->Name,
                optional($row->client->clientcountry)->Name,
                optional($row->product_application)->Name,
                $row->Competitor,
                $primarySales,
                $row->DetailsOfRequirement,
                $row->Recommendation,
                $row->DateReceived,
                // '',
                $days_late .' day' .$s,
                implode(", ", $crr_nature_array),
                $status,
                optional($row->progressStatus)->name
            ];
        }

        if(auth()->user()->role->type == "LS")
        {
            return [
                $row->CrrNumber,
                $row->DateCreated,
                $row->DueDate,
                optional($row->client)->Name,
                optional($row->product_application)->Name,
                $row->Recommendation,
                $status,
                optional($row->progressStatus)->Name
            ];
        }
    }
}
