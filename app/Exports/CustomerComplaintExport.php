<?php

namespace App\Exports;

use App\CustomerComplaint2;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerComplaintExport implements FromCollection, WithHeadings, WithMapping
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
            return CustomerComplaint2::when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
                    $query->whereIn('Status', [$openStatus, $closeStatus]);
                })
                ->when($openStatus != null && $closeStatus == null, function($query)use($openStatus) {
                    $query->where('Status', $openStatus);
                })
                ->when($closeStatus != null && $openStatus == null, function($query)use($closeStatus) {
                    $query->where('Status', $closeStatus);
                })
                ->where('CcNumber', 'LIKE', '%CCF-IS%')
                ->latest()
                ->get();
        }
        elseif((auth()->user()->role->type == "LS"))
        {
            return CustomerComplaint2::when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
                    $query->whereIn('Status', [$openStatus, $closeStatus]);
                })
                ->when($openStatus != null && $closeStatus == null, function($query)use($openStatus) {
                    $query->where('Status', $openStatus);
                })
                ->when($closeStatus != null && $openStatus == null, function($query)use($closeStatus) {
                    $query->where('Status', $closeStatus);
                })
                ->where('CcNumber', 'LIKE', '%CCF-LS%')
                ->latest()
                ->get();
        }
        else
        {
            return CustomerComplaint2::when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
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
            'CCF #',
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
                    $row->CcNumber,
                    $row->created_at,
                    $row->CompanyName,
                    $row->ContactName,
                    $row->concernedDept->Name ?? 'N/A',
                    $row->CustomerRemarks,
                    optional($row->users)->full_name,
                    $status,
                ];

    
            return $ccRows;
    }
}

