<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PayrollExport implements FromCollection, WithHeadings, WithMapping
{
    protected $payrolls;

    public function __construct($payrolls)
    {
        $this->payrolls = $payrolls;
    }

    public function collection()
    {
        return $this->payrolls;
    }

    public function headings(): array
    {
        return ['NIK', 'Nama', 'Departemen', 'Gaji Pokok', 'Tunjangan', 'Potongan', 'Lembur', 'Bonus', 'Gaji Kotor', 'Gaji Bersih', 'Status'];
    }

    public function map($payroll): array
    {
        return [
            $payroll->employee->nik,
            $payroll->employee->name,
            $payroll->employee->department?->name,
            $payroll->basic_salary,
            $payroll->total_allowance,
            $payroll->total_deduction,
            $payroll->overtime_pay,
            $payroll->bonus,
            $payroll->gross_salary,
            $payroll->net_salary,
            $payroll->status,
        ];
    }
}
