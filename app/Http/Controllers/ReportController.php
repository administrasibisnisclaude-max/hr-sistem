<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeeExport;
use App\Exports\AttendanceExport;
use App\Exports\PayrollExport;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function employees(Request $request)
    {
        $query = Employee::with(['department', 'position']);

        if ($request->department_id) $query->where('department_id', $request->department_id);
        if ($request->status) $query->where('employment_status', $request->status);

        $employees = $query->orderBy('name')->get();
        $departments = Department::where('is_active', true)->get();

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('reports.employees-pdf', compact('employees'));
            return $pdf->download('laporan-karyawan.pdf');
        }

        if ($request->export === 'excel') {
            return Excel::download(new EmployeeExport($employees), 'laporan-karyawan.xlsx');
        }

        return view('reports.employees', compact('employees', 'departments'));
    }

    public function attendance(Request $request)
    {
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');
        $departmentId = $request->department_id;

        $query = Employee::active()->with(['attendances' => function ($q) use ($month, $year) {
            $q->whereYear('date', $year)->whereMonth('date', $month);
        }, 'department']);

        if ($departmentId) $query->where('department_id', $departmentId);

        $employees = $query->orderBy('name')->get();
        $departments = Department::where('is_active', true)->get();

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('reports.attendance-pdf', compact('employees', 'month', 'year'));
            return $pdf->download("laporan-absensi-{$year}-{$month}.pdf");
        }

        if ($request->export === 'excel') {
            return Excel::download(new AttendanceExport($employees, $month, $year), "laporan-absensi-{$year}-{$month}.xlsx");
        }

        return view('reports.attendance', compact('employees', 'month', 'year', 'departments'));
    }

    public function payroll(Request $request)
    {
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');
        $departmentId = $request->department_id;

        $query = Payroll::with('employee.department')
            ->where('period_month', $month)->where('period_year', $year);

        if ($departmentId) {
            $query->whereHas('employee', fn($q) => $q->where('department_id', $departmentId));
        }

        $payrolls = $query->get();
        $totalNet = $payrolls->sum('net_salary');
        $totalGross = $payrolls->sum('gross_salary');
        $departments = Department::where('is_active', true)->get();

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('reports.payroll-pdf', compact('payrolls', 'month', 'year', 'totalNet', 'totalGross'));
            return $pdf->download("laporan-gaji-{$year}-{$month}.pdf");
        }

        if ($request->export === 'excel') {
            return Excel::download(new PayrollExport($payrolls), "laporan-gaji-{$year}-{$month}.xlsx");
        }

        return view('reports.payroll', compact('payrolls', 'month', 'year', 'totalNet', 'totalGross', 'departments'));
    }

    public function leaves(Request $request)
    {
        $year = $request->year ?? date('Y');
        $query = LeaveRequest::with('employee.department')
            ->whereYear('start_date', $year);

        if ($request->status) $query->where('status', $request->status);
        if ($request->leave_type) $query->where('leave_type', $request->leave_type);

        $leaves = $query->orderByDesc('created_at')->get();

        return view('reports.leaves', compact('leaves', 'year'));
    }
}
