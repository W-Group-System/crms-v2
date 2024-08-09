<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::with('role', 'department', 'company')->select('username', 'full_name', 'role_id', 'company_id', 'department_id')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Username',
            'Name',
            'Role',
            'Company',
            'Department'
        ];
    }

    public function map($user): array
    {
        $role = "";
        if($user->role)
        {
            $role = $user->role->name;
        }

        $department = "";
        if($user->department)
        {
            $department = $user->department->name;
        }

        $company = "";
        if($user->company)
        {
            $company = $user->company->name;
        }


        return [
            $user->username,
            $user->full_name,
            $role,
            $company,
            $department
        ];
    }
}
