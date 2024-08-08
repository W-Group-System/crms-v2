<?php

namespace App\Exports;

use App\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CurrentProductExport implements WithHeadings, FromCollection, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $currentProduct = Product::select('code', 'created_by', 'created_at')->where('status', 4)->orderBy('id', 'desc')->get();
        
        return $currentProduct;
    }

    public function headings(): array
    {
        return [
            'Code',
            'Created By',
            'Date Created'
        ];
    }

    public function map($currentProduct): array
    {
        $fullName = "";
        if($currentProduct->userByUserId)
        {
            $fullName = $currentProduct->userByUserId->full_name;
        }
        else
        {
            $fullName = $currentProduct->userById->full_name;
        }

        return [
            $currentProduct->code,
            $fullName,
            date('Y-m-d', strtotime($currentProduct->created_at))
        ];
    }
}
