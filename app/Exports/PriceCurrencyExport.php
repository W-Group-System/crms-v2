<?php

namespace App\Exports;

use App\PriceCurrency;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PriceCurrencyExport implements WithHeadings, FromCollection, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $priceCurrency = PriceCurrency::select('Name', 'Description',  'created_at' , 'updated_at')->orderBy('id', 'desc')->get();
        
        return $priceCurrency;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'Date Created',
            'Updated At',
        ];
    }

    public function map($priceCurrency): array
    {
        return [
            $priceCurrency->Name,
            $priceCurrency->Description,
            date('Y-m-d', strtotime($priceCurrency->created_at)),
            date('Y-m-d', strtotime($priceCurrency->updated_at))
        ];
    }
}
