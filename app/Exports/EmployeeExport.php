<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeExport implements FromCollection, WithHeadings, WithMapping
{
    protected $employees;

    public function __construct($employees)
    {
        $this->employees = $employees;
    }

    public function collection()
    {
        return $this->employees;
    }

    public function headings(): array
    {
        return ['NIK', 'Nama', 'Email', 'Telepon', 'Departemen', 'Jabatan', 'Status', 'Tanggal Masuk'];
    }

    public function map($employee): array
    {
        return [
            $employee->nik,
            $employee->name,
            $employee->email,
            $employee->phone,
            $employee->department?->name,
            $employee->position?->name,
            $employee->status_label,
            $employee->hire_date?->format('d/m/Y'),
        ];
    }
}
