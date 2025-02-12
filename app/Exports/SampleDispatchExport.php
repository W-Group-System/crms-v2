<?php

namespace App\Exports;

use App\SampleRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SampleDispatchExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $sample_request_no;

    public function __construct($sample_request_no)
    {
        $this->sample_request_no = $sample_request_no;
    }
    public function collection()
    {
        $srf = SampleRequest::with('requestProducts')->where('SrfNumber', $this->sample_request_no)->get();
        
        return $srf;
    }
    public function headings(): array
    {
        return [
            'Contact Person',
            'Company Name',
            'Address',
            'Label',
            'Quantity',
            'Lot No.',
            'Product Description',
            'Documents',
            'Courier Company',
            'Airway Bill No.',
            'Date of Dispatch',
            'ETA'
        ];
    }
    public function map($row): array
    {
        $sample_request_products_array = [];
        $quantity_array = [];
        $product_desc_array = [];
        foreach($row->requestProducts as $sampleRequestProducts)
        {
            $sample_request_products_array[] = $sampleRequestProducts->Label;
            $quantity_array[] = $sampleRequestProducts->Quantity;
            $product_desc_array[] = $sampleRequestProducts->ProductDescription;
        }

        return [
            optional($row->clientContact)->ContactName,
            optional($row->client)->Name,
            optional($row->clientAddress)->Address,
            implode("\n",$sample_request_products_array),
            implode("\n",$quantity_array),
            $row->SrfNumber,
            implode("\n",$product_desc_array),
            'Proforma Invoice, Certification, MSDS, Cover Letter',
            $row->Courier,
            $row->AwbNumber,
            date('F d Y', strtotime($row->DateDispatched)),
            date('F d Y', strtotime($row->Eta))
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'D' => ['alignment' => ['wrapText' => true]],
            'E' => ['alignment' => ['wrapText' => true]], 
            'G' => ['alignment' => ['wrapText' => true]],
        ];
    }
}
