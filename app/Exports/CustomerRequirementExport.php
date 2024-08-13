<?php

namespace App\Exports;

use App\CustomerRequirement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerRequirementExport implements FromCollection, WithHeadings
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

        return CustomerRequirement::select('CrrNumber', 'DateCreated', 'DueDate', 'ClientId', 'ApplicationId', 'Recommendation', 'Status', 'Progress')
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

    public function headings(): array
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
