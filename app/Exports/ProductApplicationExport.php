<?php

namespace App\Exports;

use App\ProductApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductApplicationExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ProductApplication::all();
    }

    public function headings(): array
    {
        return [
            'Application',
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
