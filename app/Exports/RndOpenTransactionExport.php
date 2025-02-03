<?php

namespace App\Exports;

use App\CustomerRequirement;
use App\RequestProductEvaluation;
use App\SampleRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RndOpenTransactionExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $role = auth()->user()->role;

        $crr = CustomerRequirement::where('Status', 10)
            ->when($role, function($q)use($role) {
                if ($role->type == 'RND')
                {
                    $q->where(function($q) {
                        $q->where('RefCode', 'RND')->orWhereNull('RefCode');
                    });
                }
                elseif($role->type == 'QCD-WHI')
                {
                    $q->where('RefCode', 'QCD-WHI');
                }
                elseif($role->type == 'QCD-PBI')
                {
                    $q->where('RefCode', 'QCD-PBI');
                }
                elseif($role->type == 'QCD-MRDC')
                {
                    $q->where('RefCode', 'QCD-MRDC');
                }
                elseif($role->type == 'QCD-CCC')
                {
                    $q->where('RefCode', 'QCD-CCC');
                }
            })
            ->when(($role->type == 'RND' || $role->type == 'QCD-WHI' || $role->type == 'QCD-PBI' || $role->type == 'QCD-MRDC') && $role->name == 'Staff L1', function($q) {
                $q->whereHas('crr_personnels', function($q) {
                    $q->where('PersonnelUserId',  auth()->user()->user_id)->orWhere('PersonnelUserId', auth()->user()->id);
                });
            })
            ->orderBy('id','desc')
            ->get();
        
        $rpe = collect([]);
        if ($role->type == 'RND')
        {
            $rpe = RequestProductEvaluation::where('Status', 10)
                ->when($role->type == 'RND' && $role->name == 'Staff L1', function($q) {
                    $q->whereHas('rpe_personnels', function($q) {
                        $q->where('PersonnelUserId',  auth()->user()->user_id)->orWhere('PersonnelUserId', auth()->user()->id);
                    });
                })
                ->orderBy('id','desc')
                ->get();
        }

        $srf = SampleRequest::where('Status', 10)
            ->when($role, function($q)use($role) {
                if ($role->type == 'RND')
                {
                    $q->where('RefCode', 1);
                }
                elseif($role->type == 'QCD-WHI')
                {
                    $q->where('RefCode', 2);
                }
                elseif($role->type == 'QCD-PBI')
                {
                    $q->where('RefCode', 3);
                }
                elseif($role->type == 'QCD-MRDC')
                {
                    $q->where('RefCode', 4);
                }
                elseif($role->type == 'QCD-CCC')
                {
                    $q->where('RefCode', 5);
                }
            })
            ->when(($role->type == 'RND' || $role->type == 'QCD-WHI' || $role->type == 'QCD-PBI' || $role->type == 'QCD-MRDC') && $role->name == 'Staff L1', function($q) {
                $q->whereHas('srf_personnel', function($q) {
                    $q->where('PersonnelUserId',  auth()->user()->user_id)->orWhere('PersonnelUserId', auth()->user()->id);
                });
            })
            ->orderBy('id','desc')
            ->get();

        return $crr->concat($rpe)->concat($srf);
    }

    public function headings(): array
    {
        return [
            '#',
            'Date Created (Y-M-D)',
            'Due Date (Y-M-D)',
            'Client Name',
            'Application',
            'Analyst',
            'Status',
            'Progress'
        ];
    }

    public function map($row): array
    {
        $id = "";
        $transaction_number = "";
        $date_created = "";
        $due_date = "";
        $client_name = "";
        $client_id = "";
        $application = "";
        $analyst = "";
        $status = "";
        $progress = "";
        if (str_contains($row->CrrNumber, 'CRR'))
        {
            if ($row->crr_personnels != null)
            {
                if ($row->crr_personnels->crrPersonnelByUserId != null)
                {
                    $analyst = $row->crr_personnels->crrPersonnelByUserId->full_name;
                }
                elseif($row->crr_personnels->crrPersonnelById != null)
                {
                    $analyst = $row->crr_personnels->crrPersonnelById->full_name;
                }
            }
            
            $id = $row->id;
            $transaction_number = $row->CrrNumber;
            $date_created = $row->DateCreated;
            $due_date = $row->DueDate;
            $client_name = optional($row->client)->Name;
            $client_id = optional($row->client)->id;
            $application = optional($row->product_application)->Name;
            $status = $row->Status;
            // $analyst = optional($row->crr_personnels)->full_name;
            $progress = $row->Progress;
        }
        
        if (str_contains($row->RpeNumber, 'RPE'))
        {
            if ($row->rpe_personnels != null)
            {
                if ($row->rpe_personnels->assignedPersonnel != null)
                {
                    $analyst = $row->rpe_personnels->assignedPersonnel->full_name;
                }
                elseif($row->rpe_personnels->userId != null)
                {
                    $analyst = $row->rpe_personnels->userId->full_name;
                }
            }

            $id = $row->id;
            $transaction_number = $row->RpeNumber;
            $date_created = $row->created_at;
            $due_date = $row->DueDate;
            $client_name = optional($row->client)->Name;
            $client_id = optional($row->client)->id;
            $application = optional($row->product_application)->Name;
            $status = $row->Status;
            $progress = $row->Progress;
        }
        if (str_contains($row->SrfNumber, 'SRF'))
        {
            if ($row->srf_personnel != null)
            {
                if ($row->srf_personnel->assignedPersonnel != null)
                {
                    $analyst = $row->srf_personnel->assignedPersonnel->full_name;
                }
                elseif($row->srf_personnel->userId != null)
                {
                    $analyst = $row->srf_personnel->userId->full_name;
                }
            }

            $id = $row->Id;
            $transaction_number = $row->SrfNumber;
            $date_created = $row->created_at;
            $due_date = $row->DateRequired;
            $client_name = optional($row->client)->Name;
            $client_id = optional($row->client)->id;
            $application = optional($row->productApplicationsId)->Name;
            $status = $row->Status;
            $progress = $row->Progress;
        }

        if ($status == 10)
        {
            $status = "Open";
        }
        elseif($status == 20)
        {
            $status = "Closed";
        }

        return [
            $transaction_number,
            $date_created,
            $due_date,
            $client_name,
            $application,
            $analyst,
            $status,
            transactionProgressName($progress)
        ];
    }
}
