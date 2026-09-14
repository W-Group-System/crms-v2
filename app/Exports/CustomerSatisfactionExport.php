<?php

namespace App\Exports;

use App\CustomerSatisfaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerSatisfactionExport implements FromCollection, WithHeadings, WithMapping
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

        if((auth()->user()->role->type == "IS"))
        {
            return CustomerSatisfaction::when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
                    $query->whereIn('Status', [$openStatus, $closeStatus]);
                })
                ->when($openStatus != null && $closeStatus == null, function($query)use($openStatus) {
                    $query->where('Status', $openStatus);
                })
                ->when($closeStatus != null && $openStatus == null, function($query)use($closeStatus) {
                    $query->where('Status', $closeStatus);
                })
                ->where('CsNumber', 'LIKE', '%CSR-IS%')
                ->latest()
                ->get();
        }
        elseif((auth()->user()->role->type == "LS"))
        {
            return CustomerSatisfaction::when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
                    $query->whereIn('Status', [$openStatus, $closeStatus]);
                })
                ->when($openStatus != null && $closeStatus == null, function($query)use($openStatus) {
                    $query->where('Status', $openStatus);
                })
                ->when($closeStatus != null && $openStatus == null, function($query)use($closeStatus) {
                    $query->where('Status', $closeStatus);
                })
                ->where('CsNumber', 'LIKE', '%CSR-LS%')
                ->latest()
                ->get();
        }
        else
        {
            return CustomerSatisfaction::when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
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
        return [
            'CSR #',
            'Date Complaint',
            'Company Name',
            'Contact Name',
            'Department Concerned',
            'Customer Remarks',
            'Received By',
            'Status',
        ];

       

    }
    public function map($row): array
    {
       
        $status = "";
        if ($row->Status == 10)
        {
            $status = "Open";
        }
        elseif($row->Status == 30)
        {
            $status = "Closed";
        }
        // elseif($row->Status == 50)
        // {
        //     $status = "Cancelled";
        // }


        //  $ccRows[];

         $ccRows = [
                    $row->CsNumber,
                    $row->created_at,
                    $row->CompanyName,
                    $row->ContactName,
                    $row->concernedDept->Name ?? '',
                    $row->Description,
                    optional($row->users)->full_name,
                    $status,
                ];

    
            return $ccRows;
    }
}

