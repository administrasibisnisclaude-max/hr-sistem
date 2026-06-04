<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Models\Employee;
use App\Models\HrNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('karyawan')) {
            return $this->ownLeaves($request);
        }

        $query = LeaveRequest::with(['employee.department', 'approver']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->month) {
            $query->whereMonth('start_date', $request->month);
        }

        $leaves = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $employees = Employee::active()->orderBy('name')->get();

        return view('leaves.index', compact('leaves', 'employees'));
    }

    private function ownLeaves(Request $request)
    {
        $employee = Auth::user()->employee;
        if (!$employee) return redirect()->route('dashboard')->with('error', 'Profil karyawan tidak ditemukan.');

        $leaves = LeaveRequest::where('employee_id', $employee->id)
            ->orderByDesc('created_at')->paginate(15);
        $leaveBalance = LeaveBalance::where('employee_id', $employee->id)
            ->where('year', date('Y'))->first();

        return view('leaves.own', compact('leaves', 'leaveBalance', 'employee'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->hasRole('karyawan')) {
            $employee = $user->employee;
            $leaveBalance = LeaveBalance::where('employee_id', $employee->id)->where('year', date('Y'))->first();
            return view('leaves.create-own', compact('employee', 'leaveBalance'));
        }

        $employees = Employee::active()->orderBy('name')->get();
        return view('leaves.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'employee_id' => $user->hasRole('karyawan') ? 'nullable' : 'required|exists:employees,id',
            'leave_type' => 'required|in:tahunan,sakit,melahirkan,darurat,lainnya',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|max:5120',
        ]);

        if ($user->hasRole('karyawan')) {
            $validated['employee_id'] = $user->employee->id;
        }

        // Calculate days
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $days = $startDate->diffInDays($endDate) + 1;
        $validated['days'] = $days;

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('leave-attachments', 'public');
        }

        $leave = LeaveRequest::create($validated);

        // Notify admins
        $admins = \App\Models\User::role('admin')->get();
        foreach ($admins as $admin) {
            HrNotification::create([
                'user_id' => $admin->id,
                'type' => 'leave_request',
                'title' => 'Pengajuan Cuti Baru',
                'message' => "Karyawan {$leave->employee->name} mengajukan cuti {$days} hari",
                'data' => ['leave_id' => $leave->id],
            ]);
        }

        return redirect()->route('leaves.index')
            ->with('success', 'Pengajuan cuti berhasil dikirim.');
    }

    public function show(LeaveRequest $leave)
    {
        $leave->load(['employee.department', 'approver']);
        return view('leaves.show', compact('leave'));
    }

    public function approve(Request $request, LeaveRequest $leave)
    {
        $request->validate(['notes' => 'nullable|string']);

        $leave->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'notes' => $request->notes,
        ]);

        // Update leave balance for annual leave
        if ($leave->leave_type === 'tahunan') {
            $balance = LeaveBalance::firstOrCreate(
                ['employee_id' => $leave->employee_id, 'year' => date('Y')],
                ['total_days' => 12, 'used_days' => 0]
            );
            $balance->increment('used_days', $leave->days);
        }

        // Mark attendance as cuti
        $start = Carbon::parse($leave->start_date);
        $end = Carbon::parse($leave->end_date);
        while ($start->lte($end)) {
            \App\Models\Attendance::updateOrCreate(
                ['employee_id' => $leave->employee_id, 'date' => $start->toDateString()],
                ['status' => 'cuti', 'notes' => $leave->leave_type_label]
            );
            $start->addDay();
        }

        // Notify employee
        if ($leave->employee->user) {
            HrNotification::create([
                'user_id' => $leave->employee->user->id,
                'type' => 'leave_approved',
                'title' => 'Cuti Disetujui',
                'message' => "Pengajuan cuti Anda tanggal {$leave->start_date->format('d/m/Y')} telah disetujui",
            ]);
        }

        activity()->causedBy(Auth::user())->log("Cuti karyawan {$leave->employee->name} disetujui");

        return back()->with('success', 'Cuti berhasil disetujui.');
    }

    public function reject(Request $request, LeaveRequest $leave)
    {
        $request->validate(['notes' => 'required|string']);

        $leave->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'notes' => $request->notes,
        ]);

        // Notify employee
        if ($leave->employee->user) {
            HrNotification::create([
                'user_id' => $leave->employee->user->id,
                'type' => 'leave_rejected',
                'title' => 'Cuti Ditolak',
                'message' => "Pengajuan cuti Anda ditolak. Alasan: {$request->notes}",
            ]);
        }

        return back()->with('success', 'Cuti berhasil ditolak.');
    }

    public function balance(Request $request)
    {
        $year = $request->year ?? date('Y');

        $employees = Employee::with(['department', 'leaveBalances' => function ($q) use ($year) {
            $q->where('year', $year);
        }])->where('employment_status', '!=', 'tidak_aktif')->orderBy('name')->get();

        $balances = LeaveBalance::with('employee.department')->where('year', $year)->get()->keyBy('employee_id');

        return view('leaves.balance', compact('employees', 'balances', 'year'));
    }

    public function storeBalance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'year'        => 'required|integer|min:2000|max:2100',
            'total_days'  => 'required|integer|min:0|max:365',
        ]);

        LeaveBalance::updateOrCreate(
            ['employee_id' => $request->employee_id, 'year' => $request->year],
            ['total_days' => $request->total_days]
        );

        return back()->with('success', 'Saldo cuti berhasil disimpan.');
    }

    public function updateBalance(Request $request, LeaveBalance $balance)
    {
        $request->validate([
            'total_days' => 'required|integer|min:0|max:365',
            'used_days'  => 'required|integer|min:0',
        ]);

        $balance->update([
            'total_days' => $request->total_days,
            'used_days'  => $request->used_days,
        ]);

        return back()->with('success', 'Saldo cuti berhasil diperbarui.');
    }

    public function initBalance(Request $request)
    {
        $request->validate([
            'year'       => 'required|integer|min:2000|max:2100',
            'total_days' => 'required|integer|min:0|max:365',
        ]);

        $employees = Employee::where('employment_status', '!=', 'tidak_aktif')->get();
        $count = 0;
        foreach ($employees as $emp) {
            $existing = LeaveBalance::where('employee_id', $emp->id)->where('year', $request->year)->first();
            if (!$existing) {
                LeaveBalance::create([
                    'employee_id' => $emp->id,
                    'year'        => $request->year,
                    'total_days'  => $request->total_days,
                    'used_days'   => 0,
                ]);
                $count++;
            }
        }

        return back()->with('success', "Saldo cuti berhasil diinisialisasi untuk {$count} karyawan.");
    }

    public function destroy(LeaveRequest $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan cuti yang masih pending yang dapat dihapus.');
        }
        $leave->delete();
        return redirect()->route('leaves.index')->with('success', 'Pengajuan cuti berhasil dihapus.');
    }
}
