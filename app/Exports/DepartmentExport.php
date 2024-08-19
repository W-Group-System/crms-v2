<?php

namespace App\Exports;

use App\department;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DepartmentExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return department::select('company_id', 'department_code', 'name', 'description', 'status')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Company',
            'Code',
            'Name',
            'Description',
            'Status'
        ];
    }

    public function map($row): array
    {
        $company = "";
        if ($row->company)
        {
            $company = $row->company->name;
        }

        return [
            $company,
            $row->department_code,
            $row->name,
            $row->description,
            $row->status
        ];
    }
}
