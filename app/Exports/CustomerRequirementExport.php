<?php

// namespace App\Exports;

// use App\CrrNature;
// use App\CustomerRequirement;
// use DateTime;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\WithMapping;

// class CustomerRequirementExport implements FromCollection, WithHeadings, WithMapping
// {
//     /**
//     * @return \Illuminate\Support\Collection
//     */
//     protected $open;
//     protected $close;
//     protected $nature;

//     public function __construct($open, $close, $nature)
//     {
//         $this->open = $open;
//         $this->close = $close;
//         $this->nature = $nature;
//     }

//     public function collection()
//     {
//         $openStatus = $this->open;
//         $closeStatus = $this->close;

//         if(auth()->user()->role->type == "IS")
//         {
//             return CustomerRequirement::with('crrNature')->select('id','CrrNumber', 'DateCreated', 'DueDate', 'ClientId', 'ApplicationId', 'Competitor', 'PrimarySalesPersonId', 'DetailsOfRequirement', 'Recommendation', 'DateReceived', 'Status', 'Progress', 'DateCompleted')
//                 ->when($this->nature && $this->nature != 'all', function($query) {
                
//                     if ($this->nature == 'recommendation') {
//                         $query->whereHas('crrNature', function($q) {
//                             $q->where('NatureOfRequestId', 2);
//                         });

//                     } elseif ($this->nature == 'documentation') {
//                         $query->whereHas('crrNature', function($q) {
//                             $q->where('NatureOfRequestId', 1);
//                         });

//                     } elseif ($this->nature == 'questionnaires') {
//                         $query->whereHas('crrNature', function($q) {
//                             $q->where('NatureOfRequestId', 3);
//                         });

//                     } elseif ($this->nature == 'coding') {
//                         $query->whereHas('crrNature', function($q) {
//                             $q->where('NatureOfRequestId', 4);
//                         });
//                     }

//                 })
//                 ->when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
//                     $query->whereIn('Status', [$openStatus, $closeStatus]);
//                 })
//                 ->when($openStatus != null && $closeStatus == null, function($query)use($openStatus) {
//                     $query->where('Status', $openStatus);
//                 })
//                 ->when($closeStatus != null && $openStatus == null, function($query)use($closeStatus) {
//                     $query->where('Status', $closeStatus);
//                 })
//                 ->where('CrrNumber','LIKE','%CRR-IS%')
//                 ->latest()
//                 ->get();
//         }

//         if(auth()->user()->role->type == "LS")
//         {
//             return CustomerRequirement::with('crrNature')->select('id', 'CrrNumber', 'DateCreated', 'DueDate', 'ClientId', 'ApplicationId', 'Recommendation', 'Status', 'Progress', 'DateCompleted')
//                     ->when($this->nature && $this->nature != 'all', function($query) {
                    
//                     if ($this->nature == 'recommendation') {
//                         $query->whereHas('crrNature', function($q) {
//                             $q->where('NatureOfRequestId', 2);
//                         });

//                     } elseif ($this->nature == 'documentation') {
//                         $query->whereHas('crrNature', function($q) {
//                             $q->where('NatureOfRequestId', 1);
//                         });

//                     } elseif ($this->nature == 'questionnaires') {
//                         $query->whereHas('crrNature', function($q) {
//                             $q->where('NatureOfRequestId', 3);
//                         });

//                     } elseif ($this->nature == 'coding') {
//                         $query->whereHas('crrNature', function($q) {
//                             $q->where('NatureOfRequestId', 4);
//                         });
//                     }

//                 })
//                 ->when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
//                     $query->whereIn('Status', [$openStatus, $closeStatus]);
//                 })
//                 ->when($openStatus != null && $closeStatus == null, function($query)use($openStatus) {
//                     $query->where('Status', $openStatus);
//                 })
//                 ->when($closeStatus != null && $openStatus == null, function($query)use($closeStatus) {
//                     $query->where('Status', $closeStatus);
//                 })
//                 ->where('CrrNumber','LIKE','%CRR-LS%')
//                 ->latest()
//                 ->get();
//         }

//         if((auth()->user()->role->type == "RND"))
//         {
//             return CustomerRequirement::with('crrNature')->select('id','CrrNumber', 'DateCreated', 'DueDate', 'ClientId', 'ApplicationId', 'Competitor', 'PrimarySalesPersonId', 'DetailsOfRequirement', 'Recommendation', 'DateReceived', 'Status', 'Progress', 'DateCompleted', 'RefCode')
//                 ->when($this->nature && $this->nature != 'all', function($query) {
            
//                     if ($this->nature == 'recommendation') {
//                         $query->whereHas('crrNature', function($q) {
//                             $q->where('NatureOfRequestId', 2);
//                         });

//                     } elseif ($this->nature == 'documentation') {
//                         $query->whereHas('crrNature', function($q) {
//                             $q->where('NatureOfRequestId', 1);
//                         });

//                     } elseif ($this->nature == 'questionnaires') {
//                         $query->whereHas('crrNature', function($q) {
//                             $q->where('NatureOfRequestId', 3);
//                         });

//                     } elseif ($this->nature == 'coding') {
//                         $query->whereHas('crrNature', function($q) {
//                             $q->where('NatureOfRequestId', 4);
//                         });
//                     }

//                 })
//                 ->when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
//                     $query->whereIn('Status', [$openStatus, $closeStatus]);
//                 })
//                 ->when($openStatus != null && $closeStatus == null, function($query)use($openStatus) {
//                     $query->where('Status', $openStatus);
//                 })
//                 ->when($closeStatus != null && $openStatus == null, function($query)use($closeStatus) {
//                     $query->where('Status', $closeStatus);
//                 })
//                 ->where('RefCode','RND')
//                 ->latest()
//                 ->get();
//         }
//         // if((auth()->user()->role->type == "QCD-CCC") || (auth()->user()->role->type == "QCD-MRDC") || (auth()->user()->role->type == "QCD-WHI") || (auth()->user()->role->type == "QCD-PBI"))
//         // {
//         //     return CustomerRequirement::with('crrNature')->select('id','CrrNumber', 'DateCreated', 'DueDate', 'ClientId', 'ApplicationId', 'Competitor', 'PrimarySalesPersonId', 'DetailsOfRequirement', 'Recommendation', 'DateReceived', 'Status', 'Progress', 'DateCompleted')
//         //         ->when($openStatus != null && $closeStatus != null, function($query)use($openStatus,$closeStatus) {
//         //             $query->whereIn('Status', [$openStatus, $closeStatus]);
//         //         })
//         //         ->when($openStatus != null && $closeStatus == null, function($query)use($openStatus) {
//         //             $query->where('Status', $openStatus);
//         //         })
//         //         ->when($closeStatus != null && $openStatus == null, function($query)use($closeStatus) {
//         //             $query->where('Status', $closeStatus);
//         //         })
//         //         ->latest()
//         //         ->get();
//         // }

//     }

//     public function headings(): array
//     {
//         if(auth()->user()->role->type == "IS")
//         {
//             return [
//                 'CRR #',
//                 'DateCreated',
//                 'Due Date',
//                 'Client Name',
//                 'Region',
//                 'Country',
//                 'Application',
//                 'Competitor',
//                 'Primary Sales Person',
//                 'Details of Requirement',
//                 'Recommendation',
//                 'DateReceived',
//                 'Days Late',
//                 'Nature of Request',
//                 'Status',
//                 'Progress'
//             ];
//         }

//         if(auth()->user()->role->type == "LS")
//         {
//             return [
//                 'CRR #',
//                 'DateCreated',
//                 'Due Date',
//                 'Client Name',
//                 'Application',
//                 'Nature of Request',
//                 'Recommendation',
//                 'Status',
//                 'Progress'
//             ];
//         }

//         if(auth()->user()->role->type == "RND")
//         {
//             return [
//                 'CRR #',
//                 'RefCode',
//                 'DateCreated',
//                 'Due Date',
//                 'Client Name',
//                 'Application',
//                 'Recommendation',
//                 'Nature of Request',
//                 'Status',
//                 'Progress'
//             ];
//         }
//     }


//     public function map($row): array
//     {
//         $primarySales = "";
//         if ($row->primarySales)
//         {
//             $primarySales = $row->primarySales->full_name;
//         }
//         elseif($row->primarySalesById)
//         {
//             $primarySales = $row->primarySalesById->full_name;
//         }

//         $status = "";
//         if ($row->Status == 10)
//         {
//             $status = "Open";
//         }
//         elseif($row->Status == 30)
//         {
//             $status = "Closed";
//         }
//         elseif($row->Status == 50)
//         {
//             $status = "Cancelled";
//         }
        
//         $crr_nature_array = [];
        
//         foreach($row->crrNature as $crrNature)
//         {
//             $crr_nature_array[] = optional($crrNature->natureOfRequest)->Name;
//         }

//         $today = new DateTime();
//         $due_date = new DateTime($row->DueDate);
//         $completed_date = new DateTime($row->DateCompleted ?? 'now');
//         $diff = $due_date->diff($completed_date);

//         $days_late = 0;
//         $s = "";
//         if ($completed_date > $due_date) 
//         {
//             $days_late = $diff->days;
//             $s = $days_late > 1 ? 's' : '';
//         } 
        
//         if(auth()->user()->role->type == "IS")
//         {
//             return [
//                 $row->CrrNumber,
//                 $row->DateCreated,
//                 $row->DueDate,
//                 optional($row->client)->Name,
//                 optional(optional($row->client)->clientregion)->Name,
//                 optional(optional($row->client)->clientcountry)->Name,
//                 optional($row->product_application)->Name,
//                 $row->Competitor,
//                 $primarySales,
//                 $row->DetailsOfRequirement,
//                 $row->Recommendation,
//                 $row->DateReceived,
//                 // '',
//                 $days_late,
//                 implode(", ", $crr_nature_array),
//                 $status,
//                 optional($row->progressStatus)->name
//             ];
//         }

//         if(auth()->user()->role->type == "LS")
//         {
//             return [
//                 $row->CrrNumber,
//                 $row->DateCreated,
//                 $row->DueDate,
//                 optional($row->client)->Name,
//                 optional($row->product_application)->Name,
//                 implode(", ", $crr_nature_array),
//                 $row->Recommendation,
//                 $status,
//                 optional($row->progressStatus)->name
//             ];
//         }

//         if(auth()->user()->role->type == "RND")
//         {
//             return [
//                 $row->CrrNumber,
//                 $row->RefCode,
//                 $row->DateCreated,
//                 $row->DueDate,
//                 optional($row->client)->Name,
//                 optional($row->product_application)->Name,
//                 $row->Recommendation,
//                 implode(", ", $crr_nature_array),
//                 $status,
//                 optional($row->progressStatus)->name
//             ];
//         }
//     }
// }


namespace App\Exports;

use App\CustomerRequirement;
use DateTime;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerRequirementExport implements FromQuery, WithHeadings, WithMapping
{
    protected $open;
    protected $close;
    protected $nature;

    public function __construct($open, $close, $nature)
    {
        $this->open = $open;
        $this->close = $close;
        $this->nature = $nature;
    }

    public function query()
    {
        $role = auth()->user()->role->type;

        $query = CustomerRequirement::query();

        if ($this->nature && $this->nature != 'all') {

            $natureIds = [
                'documentation' => 1,
                'recommendation' => 2,
                'questionnaires' => 3,
                'coding' => 4,
            ];

            if (isset($natureIds[$this->nature])) {
                $query->whereHas('crrNature', function ($q) use ($natureIds) {
                    $q->where('NatureOfRequestId', $natureIds[$this->nature]);
                });
            }
        }

        if ($this->open != null && $this->close != null) {

            $query->whereIn('Status', [
                $this->open,
                $this->close
            ]);

        } elseif ($this->open != null) {

            $query->where('Status', $this->open);

        } elseif ($this->close != null) {

            $query->where('Status', $this->close);
        }


        if ($role == 'IS') {

            $query->select(
                'id',
                'CrrNumber',
                'DateCreated',
                'DueDate',
                'ClientId',
                'ApplicationId',
                'Competitor',
                'PrimarySalesPersonId',
                'DetailsOfRequirement',
                'Recommendation',
                'DateReceived',
                'Status',
                'Progress',
                'DateCompleted'
            );

            $query->where('CrrNumber', 'LIKE', '%CRR-IS%');

        } elseif ($role == 'LS') {

            $query->select(
                'id',
                'CrrNumber',
                'DateCreated',
                'DueDate',
                'ClientId',
                'ApplicationId',
                'Recommendation',
                'Status',
                'Progress',
                'DateCompleted'
            );

            $query->where('CrrNumber', 'LIKE', '%CRR-LS%');

        } elseif ($role == 'RND') {

            $query->select(
                'id',
                'CrrNumber',
                'DateCreated',
                'DueDate',
                'ClientId',
                'ApplicationId',
                'Competitor',
                'PrimarySalesPersonId',
                'DetailsOfRequirement',
                'Recommendation',
                'DateReceived',
                'Status',
                'Progress',
                'DateCompleted',
                'RefCode'
            );

            $query->where('RefCode', 'RND');
        }

        $query->with([
            'crrNature.natureOfRequest',
            'client.clientregion',
            'client.clientcountry',
            'product_application',
            'progressStatus',
            'primarySales',
            'primarySalesById',
        ]);

        return $query->latest();
    }

    public function headings(): array
    {
        $role = auth()->user()->role->type;

        if ($role == 'IS') {
            return [
                'CRR #',
                'DateCreated',
                'Due Date',
                'Client Name',
                'Region',
                'Country',
                'Application',
                'Competitor',
                'Primary Sales Person',
                'Details of Requirement',
                'Recommendation',
                'DateReceived',
                'Days Late',
                'Nature of Request',
                'Status',
                'Progress'
            ];
        }

        if ($role == 'LS') {
            return [
                'CRR #',
                'DateCreated',
                'Due Date',
                'Client Name',
                'Application',
                'Nature of Request',
                'Recommendation',
                'Status',
                'Progress'
            ];
        }

        if ($role == 'RND') {
            return [
                'CRR #',
                'RefCode',
                'DateCreated',
                'Due Date',
                'Client Name',
                'Application',
                'Recommendation',
                'Nature of Request',
                'Status',
                'Progress'
            ];
        }

        return [];
    }

    public function map($row): array
    {
        $role = auth()->user()->role->type;

        $primarySales = '';

        if ($row->primarySales) {
            $primarySales = $row->primarySales->full_name;
        } elseif ($row->primarySalesById) {
            $primarySales = $row->primarySalesById->full_name;
        }

        $status = '';

        if ($row->Status == 10) {
            $status = 'Open';
        } elseif ($row->Status == 30) {
            $status = 'Closed';
        } elseif ($row->Status == 50) {
            $status = 'Cancelled';
        }


        $crrNatureArray = [];

        foreach ($row->crrNature as $crrNature) {
            $crrNatureArray[] = optional(
                $crrNature->natureOfRequest
            )->Name;
        }


        $daysLate = 0;

        if ($row->DueDate) {

            $dueDate = new DateTime($row->DueDate);

            $completedDate = new DateTime(
                $row->DateCompleted ?: 'now'
            );

            if ($completedDate > $dueDate) {
                $daysLate = $dueDate->diff($completedDate)->days;
            }
        }


        if ($role == 'IS') {

            return [
                $row->CrrNumber,
                $row->DateCreated,
                $row->DueDate,
                optional($row->client)->Name,
                optional(optional($row->client)->clientregion)->Name,
                optional(optional($row->client)->clientcountry)->Name,
                optional($row->product_application)->Name,
                $row->Competitor,
                $primarySales,
                $row->DetailsOfRequirement,
                $row->Recommendation,
                $row->DateReceived,
                $daysLate,
                implode(', ', $crrNatureArray),
                $status,
                optional($row->progressStatus)->name
            ];
        }


        if ($role == 'LS') {

            return [
                $row->CrrNumber,
                $row->DateCreated,
                $row->DueDate,
                optional($row->client)->Name,
                optional($row->product_application)->Name,
                implode(', ', $crrNatureArray),
                $row->Recommendation,
                $status,
                optional($row->progressStatus)->name
            ];
        }

        if ($role == 'RND') {

            return [
                $row->CrrNumber,
                $row->RefCode,
                $row->DateCreated,
                $row->DueDate,
                optional($row->client)->Name,
                optional($row->product_application)->Name,
                $row->Recommendation,
                implode(', ', $crrNatureArray),
                $status,
                optional($row->progressStatus)->name
            ];
        }

        return [];
    }
}