<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('karyawan')) {
            return $this->ownAttendance($request);
        }

        $query = Attendance::with('employee.department');
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();

        $query->where('date', $date);

        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->department_id) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        $attendances = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        $employees = Employee::active()->orderBy('name')->get();
        $departments = \App\Models\Department::where('is_active', true)->get();

        return view('attendance.index', compact('attendances', 'employees', 'departments', 'date'));
    }

    private function ownAttendance(Request $request)
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Profil karyawan tidak ditemukan.');
        }

        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');

        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->get();

        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->where('date', Carbon::today())->first();

        return view('attendance.own', compact('attendances', 'todayAttendance', 'employee', 'month', 'year'));
    }

    public function clock(Request $request)
    {
        $user = Auth::user();
        if (!$user->employee) {
            return back()->with('error', 'Profil karyawan tidak ditemukan.');
        }

        $employee = $user->employee;
        $today = Carbon::today();
        $now = Carbon::now()->toTimeString();

        $attendance = Attendance::firstOrNew([
            'employee_id' => $employee->id,
            'date' => $today,
        ]);

        if (!$attendance->clock_in) {
            $attendance->clock_in = $now;
            $attendance->status = 'hadir';
            $attendance->save();
            return back()->with('success', 'Absen masuk berhasil: ' . $now);
        } elseif (!$attendance->clock_out) {
            $attendance->clock_out = $now;
            $attendance->save();
            return back()->with('success', 'Absen pulang berhasil: ' . $now);
        }

        return back()->with('info', 'Anda sudah melakukan absen hari ini.');
    }

    public function create()
    {
        $employees = Employee::active()->orderBy('name')->get();
        return view('attendance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:hadir,izin,sakit,alpha,cuti',
            'notes' => 'nullable|string',
            'overtime_hours' => 'nullable|numeric|min:0|max:24',
        ]);

        Attendance::updateOrCreate(
            ['employee_id' => $validated['employee_id'], 'date' => $validated['date']],
            $validated
        );

        return redirect()->route('attendance.index')
            ->with('success', 'Data absensi berhasil disimpan.');
    }

    public function recap(Request $request)
    {
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');
        $departmentId = $request->department_id;

        $query = Employee::active()->with(['attendances' => function ($q) use ($month, $year) {
            $q->whereYear('date', $year)->whereMonth('date', $month);
        }, 'department', 'position']);

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        $employees = $query->orderBy('name')->get();
        $departments = \App\Models\Department::where('is_active', true)->get();

        // Working days in the month
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;

        return view('attendance.recap', compact('employees', 'month', 'year', 'departments', 'daysInMonth'));
    }

    public function edit(Attendance $attendance)
    {
        $employees = Employee::active()->orderBy('name')->get();
        return view('attendance.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:hadir,izin,sakit,alpha,cuti',
            'notes' => 'nullable|string',
            'overtime_hours' => 'nullable|numeric|min:0|max:24',
        ]);

        $attendance->update($validated);
        return redirect()->route('attendance.index')
            ->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendance.index')
            ->with('success', 'Data absensi berhasil dihapus.');
    }
}
