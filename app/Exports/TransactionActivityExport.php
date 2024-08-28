<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionActivityExport implements FromCollection, WithHeadings
{
    protected $transaction_data;

    public function __construct($transaction_data)
    {
        $this->transaction_data = $transaction_data;
    }

    public function collection()
    {
        return $this->transaction_data;
    }

    public function headings(): array
    {
        return [
            'Type', 
            'Transaction Number', 
            'BDE', 
            'Client', 
            'Date Created', 
            'Due Date', 
            'Details', 
            'Result', 
            'Status', 
            'Progress', 
        ];
    }
}
