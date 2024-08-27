<?php

namespace App\Exports;

use App\BusinessType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BusinessTypeExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return BusinessType::all();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Description'
        ];
    }

    public function map($row): array
    {
        return [
            $row->Name,
            $row->Description
        ];
    }
}
