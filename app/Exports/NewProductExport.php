<?php

namespace App\Exports;

use App\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class NewProductExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::select('ddw_number', 'code', 'created_by', 'created_at')->where('status', 2)->orderBy('id', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'DDW Number',
            'Code',
            'Created By',
            'Date Created'
        ];
    }

    public function map($newProducts): array
    {
        $fullName = "";
        if($newProducts->userByUserId)
        {
            $fullName = $newProducts->userByUserId->full_name;
        }
        else
        {
            $fullName = $newProducts->userById->full_name;
        }

        return [
            $newProducts->ddw_number,
            $newProducts->code,
            $fullName,
            date('Y-m-d', strtotime($newProducts->created_at))
        ];
    }
}
