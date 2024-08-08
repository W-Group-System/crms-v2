<?php

namespace App\Exports;

use App\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DraftProductExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::select('ddw_number', 'code', 'created_by', 'created_at')->where('status', 1)->orderBy('id', 'desc')->get();
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

    public function map($draftProducts): array
    {
        $fullName = "";
        if($draftProducts->userByUserId)
        {
            $fullName = $draftProducts->userByUserId->full_name;
        }
        else
        {
            $fullName = $draftProducts->userById->full_name;
        }

        return [
            $draftProducts->ddw_number,
            $draftProducts->code,
            $fullName,
            date('Y-m-d', strtotime($draftProducts->created_at))
        ];
    }
}
