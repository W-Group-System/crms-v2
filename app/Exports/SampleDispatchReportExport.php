<?php

namespace App\Exports;

use App\SampleRequestProduct;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SampleDispatchReportExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $from;
    protected $to;
    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        $from = $this->from;
        $to = $this->to;

        $sample_request_product = SampleRequestProduct::whereHas('sampleRequest', function($q) use ($from, $to) {
                $q->whereBetween('DateDispatched', [$from, $to]);
            })
            ->orderBy('SampleRequestId', 'desc')
            ->get();

        return $sample_request_product;
    }

    public function headings(): array
    {
        return [
            'Date of BDE Advise',
            'Date of Dispatch',
            'SRF No.',
            'Company',
            'Contact Person',
            'Address',
            'Quantity',
            'In grams',
            'Product',
            'Lot Number',
            'Product Description',
            'Courier Company',
            'AWB No.',
            'ETA',
            'Courier Cost',
            'Sample Type',
            'Issued To',
            'Reason for Delayed Dispatch',
            'Account Manager',
            'Dispatch By'
        ];
    }

    public function map($row): array
    {
        $srfType = "";
        if($row->sampleRequest->SrfType == 1)
        {
            $srfType = 'Regular';
        }
        elseif($row->sampleRequest->SrfType == 2)
        {
            $srfType = 'PSS';
        }
        else
        {
            $srfType = 'CSS';
        }

        $ref_code = "";
        $refCode = $row->sampleRequest->RefCode;
        if ($refCode == 1) 
        {
            $ref_code = 'RND';
        } 
        elseif ($refCode == 2) 
        {
            $ref_code = 'QCD-WHI';
        } 
        elseif ($refCode == 3) 
        {
            $ref_code = 'QCD-PBI';
        } 
        elseif ($refCode == 4) 
        {
            $ref_code = 'QCD-MRDC';
        } 
        else 
        {
            $ref_code = 'QCD-CCC';
        }
        
        return [
            $row->sampleRequest->DateSampleReceived,
            $row->sampleRequest->DateDispatched,
            $row->sampleRequest->SrfNumber,
            $row->sampleRequest->client->Name,
            $row->sampleRequest->clientContact->ContactName,
            optional($row->sampleRequest->clientAddress)->Address,
            $row->NumberOfPackages.' x '.$row->Quantity.' '.$row->uom->Name,
            $row->NumberOfPackages * $row->Quantity,
            $row->ProductCode,
            $row->sampleRequest->SrfNumber.'-'.$row->ProductIndex,
            $row->ProductDescription,
            $row->sampleRequest->Courier,
            $row->sampleRequest->AwbNumber,
            $row->sampleRequest->Eta,
            $row->sampleRequest->CourierCost,
            $srfType, 
            $ref_code,
            $row->sampleRequest->Reason,
            optional($row->sampleRequest->primarySalesPerson)->full_name,
            optional($row->sampleRequest->dispatchBy)->full_name
        ];
    }
}
