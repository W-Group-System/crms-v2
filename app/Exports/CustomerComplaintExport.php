<?php

namespace App\Exports;

use App\CustomerComplaint2;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerComplaintExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $open;
    protected $close;

    public function __construct($open, $close)
    {
        $this->open = $open;
        $this->close = $close;
    }

    public function collection()
    {
        $openStatus = $this->open;
        $closeStatus = $this->close;

        if((auth()->user()->role->type == "IS"))
        {
            return CustomerComplaint2::when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
                    $query->whereIn('Status', [$openStatus, $closeStatus]);
                })
                ->when($openStatus != null && $closeStatus == null, function($query)use($openStatus) {
                    $query->where('Status', $openStatus);
                })
                ->when($closeStatus != null && $openStatus == null, function($query)use($closeStatus) {
                    $query->where('Status', $closeStatus);
                })
                ->where('CcNumber', 'LIKE', '%CCF-IS%')
                ->with([
                    'product_quality',
                    'packaging',
                    'delivery_handling',
                    'others',
                    'ccRemarks' => function ($query) {
                        $query->orderByDesc('created_at')->orderByDesc('id');
                    },
                ])
                ->latest()
                ->get();
        }
        elseif((auth()->user()->role->type == "LS"))
        {
            return CustomerComplaint2::when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
                    $query->whereIn('Status', [$openStatus, $closeStatus]);
                })
                ->when($openStatus != null && $closeStatus == null, function($query)use($openStatus) {
                    $query->where('Status', $openStatus);
                })
                ->when($closeStatus != null && $openStatus == null, function($query)use($closeStatus) {
                    $query->where('Status', $closeStatus);
                })
                ->where('CcNumber', 'LIKE', '%CCF-LS%')
                ->with([
                    'product_quality',
                    'packaging',
                    'delivery_handling',
                    'others',
                    'ccRemarks' => function ($query) {
                        $query->orderByDesc('created_at')->orderByDesc('id');
                    },
                ])
                ->latest()
                ->get();
        }
        else
        {
            return CustomerComplaint2::when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
                    $query->whereIn('Status', [$openStatus, $closeStatus]);
                })
                ->when($openStatus != null && $closeStatus == null, function($query)use($openStatus) {
                    $query->where('Status', $openStatus);
                })
                ->when($closeStatus != null && $openStatus == null, function($query)use($closeStatus) {
                    $query->where('Status', $closeStatus);
                })
                ->with([
                    'product_quality',
                    'packaging',
                    'delivery_handling',
                    'others',
                    'ccRemarks' => function ($query) {
                        $query->orderByDesc('created_at')->orderByDesc('id');
                    },
                ])
                ->latest()
                ->get();
        }
    }

    public function headings(): array
    {
        $headings = [
            'CCF #',
            'Date Complaint',
            'Date Prepared',
            'Category',

            'Customer',
            'Company Name',
            'Product Name',
            'SC#',
            'SO#',
            'Quantity (kg)',
        ];

        $headings = array_merge($headings, [
            'Complaint Endorsed To',
            'Department Concerned',
            'Internal Remarks',
            'Date Addressed',
            'NCAR #',
            'Immediate Action',
            'Corrective Action',
            'Received By',
            'Status',
        ]);

        return $headings;
    }
    //Auto stretch widht of excel
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $worksheet = $event->sheet->getDelegate();
                $lastColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(
                    $worksheet->getHighestDataColumn()
                );
                $lastRow = $worksheet->getHighestDataRow();

                for ($columnIndex = 1; $columnIndex <= $lastColumnIndex; $columnIndex++) {
                    $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
                    $maxLength = 0;

                    for ($rowIndex = 1; $rowIndex <= $lastRow; $rowIndex++) {
                        $value = (string) $worksheet->getCellByColumnAndRow($columnIndex, $rowIndex)->getFormattedValue();
                        foreach (preg_split('/\r\n|\r|\n/', $value) as $line) {
                            $maxLength = max($maxLength, mb_strlen($line, 'UTF-8'));
                        }
                    }

                    $maxWidth = in_array($column, ['G', 'H', 'I', 'J'], true) ? 40 : 255;
                    $worksheet->getColumnDimension($column)->setWidth(min($maxWidth, max(10, $maxLength + 2)));
                }

                if ($lastRow > 1) {
                    $worksheet->getStyle("G2:J{$lastRow}")
                        ->getAlignment()
                        ->setWrapText(true)
                        ->setVertical('top');
                }
            },
        ];
    }

    public function map($row): array
    {
        $status = "";
        if ($row->Status == 10)
        {
            $status = "Open";
        }
        elseif($row->Status == 30)
        {
            $status = "Closed";
        }
        // elseif($row->Status == 50)
        // {
        //     $status = "Cancelled";
        // }


        //  $ccRows[];

        $categoryData = $this->categoryData($row);
        $ccRows = [
            $row->CcNumber,
            $row->created_at ? $row->created_at->format('d-M-y') : 'N/A',
            $row->DateReceived ? \Carbon\Carbon::parse($row->DateReceived)->format('d-M-y') : 'N/A',
            $categoryData['title'],


            $row->ContactName ?? 'N/A',
            $row->CompanyName ?? 'N/A',
            $categoryData['product_name'],
            $categoryData['sc_no'],
            $categoryData['so_no'],
            $categoryData['quantity'],
        ];

        $ccRows = array_merge($ccRows, [
            $row->site_concerned_name ?? 'N/A',
            $row->concernedDept->Name ?? 'N/A',
            optional($row->ccRemarks->first())->SalesRemarks ?? 'N/A',
            $row->ActionDate ? \Carbon\Carbon::parse($row->ActionDate)->format('d-M-y') : 'N/A',
            $row->IssuanceNo ?? 'N/A',
            $row->ImmediateAction ?? 'N/A',
            $row->CorrectiveAction ?? 'N/A',
            optional($row->users)->full_name,
            $status,
        ]);

        return $ccRows;
    }

    private function categoryData($row): array
    {
        $categories = [
            [
                'relationship' => 'product_quality',
                'title' => 'Product Quality',
                'fields' => ['Pn' => 'product_name', 'ScNo' => 'sc_no', 'SoNo' => 'so_no', 'Quantity' => 'quantity'],
                'count' => 6,
            ],
            [
                'relationship' => 'packaging',
                'title' => 'Packing',
                'fields' => ['PackPn' => 'product_name', 'PackScNo' => 'sc_no', 'PackSoNo' => 'so_no', 'PackQuantity' => 'quantity'],
                'count' => 4,
            ],
            [
                'relationship' => 'delivery_handling',
                'title' => 'Delivery & Handling',
                'fields' => ['DhPn' => 'product_name', 'DhScNo' => 'sc_no', 'DhSoNo' => 'so_no', 'DhQuantity' => 'quantity'],
                'count' => 3,
            ],
            [
                'relationship' => 'others',
                'title' => 'Others',
                'fields' => ['OthersPn' => 'product_name', 'OthersScNo' => 'sc_no', 'OthersSoNo' => 'so_no', 'OthersQuantity' => 'quantity'],
                'count' => 4,
            ],
        ];

        $titles = [];
        $previousTitle = null;
        $valuesByColumn = [
            'product_name' => [],
            'sc_no' => [],
            'so_no' => [],
            'quantity' => [],
        ];

        foreach ($categories as $category) {
            $related = $row->{$category['relationship']};
            if (!$related) {
                continue;
            }

            for ($index = 1; $index <= $category['count']; $index++) {
                $values = [];
                foreach ($category['fields'] as $field => $key) {
                    $values[$key] = $related->{$field . $index} ?? null;
                }

                if (count(array_filter($values, function ($value) {
                    return $value !== null && $value !== '';
                })) > 0) {
                    $titles[] = $category['title'] === $previousTitle ? '' : $category['title'];
                    $previousTitle = $category['title'];

                    foreach ($valuesByColumn as $key => $columnValues) {
                        $valuesByColumn[$key][] = $values[$key] ?? '';
                    }
                }
            }
        }

        return [
            'title' => implode("\n", $titles),
            'product_name' => implode("\n", $valuesByColumn['product_name']),
            'sc_no' => implode("\n", $valuesByColumn['sc_no']),
            'so_no' => implode("\n", $valuesByColumn['so_no']),
            'quantity' => implode("\n", $valuesByColumn['quantity']),
        ];
    }
}
