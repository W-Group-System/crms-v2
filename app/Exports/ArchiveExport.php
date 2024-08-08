<?php

namespace App\Exports;

use App\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ArchiveExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::select('ddw_number', 'code', 'created_by', 'created_at')->where('status', 5)->orderBy('id', 'desc')->get();
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

    public function map($archiveProducts): array
    {
        $fullName = "";
        if($archiveProducts->userByUserId)
        {
            $fullName = $archiveProducts->userByUserId->full_name;
        }
        else
        {
            $fullName = $archiveProducts->userById->full_name;
        }

        return [
            $archiveProducts->ddw_number,
            $archiveProducts->code,
            $fullName,
            date('Y-m-d', strtotime($archiveProducts->created_at))
        ];
    }
}
