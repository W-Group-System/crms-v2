<?php

namespace App\Exports;

use App\RawMaterial;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RawMaterialsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return RawMaterial::all();
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
