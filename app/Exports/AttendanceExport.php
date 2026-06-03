<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class AttendanceExport implements FromCollection, WithHeadings
{
    protected $employees;
    protected $month;
    protected $year;

    public function __construct($employees, $month, $year)
    {
        $this->employees = $employees;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        $rows = new Collection();
        foreach ($this->employees as $employee) {
            $hadir = $employee->attendances->where('status', 'hadir')->count();
            $izin = $employee->attendances->where('status', 'izin')->count();
            $sakit = $employee->attendances->where('status', 'sakit')->count();
            $alpha = $employee->attendances->where('status', 'alpha')->count();
            $cuti = $employee->attendances->where('status', 'cuti')->count();
            $rows->push([
                $employee->nik,
                $employee->name,
                $employee->department?->name,
                $hadir,
                $izin,
                $sakit,
                $alpha,
                $cuti,
            ]);
        }
        return $rows;
    }

    public function headings(): array
    {
        return ['NIK', 'Nama', 'Departemen', 'Hadir', 'Izin', 'Sakit', 'Alpha', 'Cuti'];
    }
}
