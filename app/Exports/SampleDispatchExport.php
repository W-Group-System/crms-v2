<?php

namespace App\Exports;

use App\SampleRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SampleDispatchExport implements WithStyles, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $sample_request_no;

    public function __construct($sample_request_no)
    {
        $this->sample_request_no = $sample_request_no;
    }
    // public function collection()
    // {
    //     $srf = SampleRequest::with('requestProducts')->where('SrfNumber', $this->sample_request_no)->get();
        
    //     return $srf;
    // }
    // public function headings(): array
    // {
    //     return [
    //         'Contact Person',
    //         'Company Name',
    //         'Address',
    //         'Label',
    //         'Quantity',
    //         'Lot No.',
    //         'Product Description',
    //         'Documents',
    //         'Courier Company',
    //         'Airway Bill No.',
    //         'Date of Dispatch',
    //         'ETA'
    //     ];
    // }

    // public function map($row): array
    // {
    //     $sample_request_products_array = [];
    //     $quantity_array = [];
    //     $product_desc_array = [];
    //     foreach($row->requestProducts as $sampleRequestProducts)
    //     {
    //         $sample_request_products_array[] = $sampleRequestProducts->Label;
    //         $quantity_array[] = $sampleRequestProducts->Quantity;
    //         $product_desc_array[] = $sampleRequestProducts->ProductDescription;
    //     }

    //     return [
    //         optional($row->clientContact)->ContactName,
    //         optional($row->client)->Name,
    //         optional($row->clientAddress)->Address,
    //         implode("\n",$sample_request_products_array),
    //         implode("\n",$quantity_array),
    //         $row->SrfNumber,
    //         implode("\n",$product_desc_array),
    //         'Proforma Invoice, Certification, MSDS, Cover Letter',
    //         $row->Courier,
    //         $row->AwbNumber,
    //         date('F d Y', strtotime($row->DateDispatched)),
    //         date('F d Y', strtotime($row->Eta))
    //     ];
    // }

    public function styles(Worksheet $sheet)
    {
        $srf = SampleRequest::with('requestProducts')->where('SrfNumber', $this->sample_request_no)->get();

        $srf_array = [];
        $sample_request_products_array = [];
        $quantity_array = [];
        $product_desc_array = [];
        $lot_array = [];
        foreach($srf as $row)
        {
            foreach($row->requestProducts as $sampleRequestProducts)
            {
                // dd($sampleRequestProducts);
                $sample_request_products_array[] = $sampleRequestProducts->Label;
                $quantity_array[] = $sampleRequestProducts->Quantity;
                $product_desc_array[] = $sampleRequestProducts->ProductDescription;
                $lot_array[] = $row->SrfNumber.'-'.$sampleRequestProducts->ProductIndex;
            }
            
            $srf_array['contact'] = optional($row->clientContact)->ContactName;
            $srf_array['company'] = optional($row->client)->Name;
            $srf_array['address'] = optional($row->clientAddress)->Address;
            $srf_array['product'] = implode("\n",$sample_request_products_array);
            $srf_array['quantity'] = implode("\n",$quantity_array);
            $srf_array['srf'] = count($lot_array) > 1 ? implode("\n",$lot_array) : $row->SrfNumber;
            $srf_array['product_description'] = implode("\n",$product_desc_array);
            $srf_array['documents'] = 'Proforma Invoice, Certification, MSDS, Cover Letter';
            $srf_array['courier'] = $row->Courier;
            $srf_array['awb'] = $row->AwbNumber;
            $srf_array['date_dispatched'] = date('F d Y', strtotime($row->DateDispatched));
            $srf_array['eta'] = date('F d Y', strtotime($row->Eta));

        }
        
        $sheet->setShowGridlines(false);

        $sheet->mergeCells('B2:E2');
        $sheet->mergeCells('B3:E3');
        $sheet->mergeCells('C4:E4');
        $sheet->mergeCells('C5:E5');
        $sheet->mergeCells('C6:E6');
        $sheet->mergeCells('C11:E11');
        $sheet->mergeCells('C12:E12');
        $sheet->mergeCells('C13:E13');
        $sheet->mergeCells('C14:E14');
        $sheet->mergeCells('C15:E15');

        $sheet->setCellValue('B2', 'Sample Dispatch Advice');
        $sheet->setCellValue('B3', '*Please email confirmation upon receipt of the samples.');

        $sheet->setCellValue('B4', 'Contact Person:');
        $sheet->setCellValue('C4', $srf_array['contact']);
        
        $sheet->setCellValue('B5', 'Company Name:');
        $sheet->setCellValue('C5', $srf_array['company']);

        $sheet->setCellValue('B6', 'Address:');
        $sheet->setCellValue('C6', $srf_array['address']);

        $sheet->setCellValue('B8', 'Product');
        $sheet->setCellValue('B9', $srf_array['product']);

        $sheet->setCellValue('C8', 'Quantity');
        $sheet->setCellValue('C9', $srf_array['quantity'] );

        $sheet->setCellValue('D8', 'Lot No.');
        $sheet->setCellValue('D9', $srf_array['srf']);

        $sheet->setCellValue('E8', 'Product Description');
        $sheet->setCellValue('E9', $srf_array['product_description']);

        $sheet->setCellValue('B11', 'Documents:');
        $sheet->setCellValue('C11', $srf_array['documents']);
        
        $sheet->setCellValue('B12', 'Courier Company:');
        $sheet->setCellValue('C12', $srf_array['courier'] );

        $sheet->setCellValue('B13', 'Airway Bill No.');
        $sheet->setCellValue('C13', $srf_array['awb']);

        $sheet->setCellValue('B14', 'Date of Dispatch:');
        $sheet->setCellValue('C14', $srf_array['date_dispatched']);

        $sheet->setCellValue('B15', 'ETA:');
        $sheet->setCellValue('C15', $srf_array['eta']);

        $sheet->getStyle('B2')->applyFromArray([
            'font' => [
                'bold' => true,
                'underline' => true
            ]
        ]);
        $sheet->getStyle('B3')->applyFromArray([
            'font' => [
                'italic' => true
            ]
        ]);
        $sheet->getStyle('B4:E4')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'left' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('B4')->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' =>  'ffc000'],
            ],
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('B5:E5')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'left' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('B5')->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' =>  'ffc000'],
            ],
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('B6:E6')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'left' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('B6')->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' =>  'ffc000'],
            ],
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);

        $sheet->getStyle('B8:E8')->applyFromArray([
            'font' => [
                'bold' => true,
                'text-center' => true
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'left' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' =>  'ffc000'],
            ],
        ]);
        $sheet->getStyle('B8')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('C8')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('D8')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('E8')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);

        $sheet->getStyle('B9:E9')->applyFromArray([
            'font' => [
                'bold' => true,
                'text-center' => true
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'left' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ]
        ]);
        $sheet->getStyle('B9')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('C9')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('D9')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('E9')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('B9:E9')->getAlignment()->setWrapText(true);

        $sheet->getStyle('B11:E11')->applyFromArray([
            'font' => [
                'bold' => true,
                'text-center' => true
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'left' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('B11')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' =>  'ffc000'],
            ],
        ]);
        $sheet->getStyle('C11')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('D11')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('E11')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);

        $sheet->getStyle('B12:E12')->applyFromArray([
            'font' => [
                'bold' => true,
                'text-center' => true
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'left' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('B12')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' =>  'ffc000'],
            ],
        ]);
        $sheet->getStyle('C12')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('D12')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('E12')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);

        $sheet->getStyle('B13:E13')->applyFromArray([
            'font' => [
                'bold' => true,
                'text-center' => true
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'left' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('B13')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' =>  'ffc000'],
            ],
        ]);
        $sheet->getStyle('C13')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('D13')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('E13')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);

        $sheet->getStyle('B14:E14')->applyFromArray([
            'font' => [
                'bold' => true,
                'text-center' => true
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'left' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('B14')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' =>  'ffc000'],
            ],
        ]);
        $sheet->getStyle('C14')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('D14')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('E14')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);

        $sheet->getStyle('B15:E15')->applyFromArray([
            'font' => [
                'bold' => true,
                'text-center' => true
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'left' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('B15')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' =>  'ffc000'],
            ],
        ]);
        $sheet->getStyle('C15')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('D15')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
        $sheet->getStyle('E15')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ],
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'B' => 20.00,
            'C' => 20.00,
            'D' => 20.00,
            'E' => 20.00,
        ];
    }
}
