<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\Announcement;
use App\Models\Contract;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $currentMonth = $today->month;
        $currentYear = $today->year;

        // For karyawan - show own dashboard
        if ($user->hasRole('karyawan') && $user->employee) {
            $employee = $user->employee;
            $todayAttendance = Attendance::where('employee_id', $employee->id)
                ->where('date', $today)->first();
            $leaveBalance = $employee->leaveBalances()->where('year', $currentYear)->first();
            $pendingLeaves = $employee->leaveRequests()->where('status', 'pending')->count();
            $latestPayroll = $employee->payrolls()->where('status', 'paid')
                ->orderByDesc('period_year')->orderByDesc('period_month')->first();
            $announcements = Announcement::active()
                ->where(function ($q) {
                    $q->where('target_role', 'all')->orWhere('target_role', 'karyawan');
                })
                ->orderByDesc('created_at')->take(5)->get();

            return view('dashboard.karyawan', compact(
                'employee', 'todayAttendance', 'leaveBalance',
                'pendingLeaves', 'latestPayroll', 'announcements'
            ));
        }

        // For admin/owner - show admin dashboard
        $totalEmployees = Employee::active()->count();
        $presentToday = Attendance::where('date', $today)->where('status', 'hadir')->count();
        $onLeave = Attendance::where('date', $today)->where('status', 'cuti')->count();
        $totalPayrollThisMonth = Payroll::where('period_month', $currentMonth)
            ->where('period_year', $currentYear)->sum('net_salary');
        $pendingLeaveRequests = LeaveRequest::where('status', 'pending')->count();

        // Contracts expiring in 30 days
        $expiringContracts = Contract::where('status', 'active')
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [$today, $today->copy()->addDays(30)])
            ->with('employee')
            ->get();

        $announcements = Announcement::active()
            ->orderByDesc('created_at')->take(5)->get();

        $recentEmployees = Employee::with(['department', 'position'])
            ->orderByDesc('created_at')->take(5)->get();

        $attendanceSummary = [
            'hadir' => Attendance::where('date', $today)->where('status', 'hadir')->count(),
            'izin' => Attendance::where('date', $today)->where('status', 'izin')->count(),
            'sakit' => Attendance::where('date', $today)->where('status', 'sakit')->count(),
            'alpha' => Attendance::where('date', $today)->where('status', 'alpha')->count(),
        ];

        return view('dashboard.index', compact(
            'totalEmployees', 'presentToday', 'onLeave', 'totalPayrollThisMonth',
            'pendingLeaveRequests', 'expiringContracts', 'announcements',
            'recentEmployees', 'attendanceSummary'
        ));
    }
}
