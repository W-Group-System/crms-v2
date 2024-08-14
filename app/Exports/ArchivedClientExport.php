<?php

namespace App\Exports;

use App\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ArchivedClientExport implements WithHeadings, FromCollection, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Client::with(['industry', 'userById', 'userById2', 'userById2', 'userByUserId2'])
            ->where('Status', 5)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Type',
            'Industry',
            'Buyer Code',
            'Name',
            'Primary Account Manager',
            'Secondary Account Manager'
        ];
    }
    
    public function map($currentClient): array
    {
        $type = $currentClient->Type == "1" ? "Local" : ($currentClient->Type == "2" ? "International" : "N/A");

        // Determine primary account manager's full name
        $primaryAccount = $currentClient->userById->full_name ?? $currentClient->userByUserId->full_name ?? 'N/A';

        // Determine secondary account manager's full name
        $secondaryAccount = $currentClient->userById2->full_name ?? $currentClient->userByUserId2->full_name ?? 'N/A';
        
        return [
            $type,
            $currentClient->industry->Name ?? 'N/A',
            $currentClient->BuyerCode ?? 'N/A',
            $currentClient->Name ?? 'N/A',
            $primaryAccount,
            $secondaryAccount
        ];
    }
}
