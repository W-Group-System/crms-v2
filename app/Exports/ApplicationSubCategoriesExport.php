<?php

namespace App\Exports;

use App\ProductSubcategories;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApplicationSubCategoriesExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ProductSubcategories::with('application')->get();
    }

    public function headings(): array
    {
        return [
            'Application',
            'Subcategory',
            'Description'
        ];
    }

    public function map($row): array
    {
        return [
            optional($row->application)->Name,
            $row->Name,
            $row->Description
        ];
    }


}
