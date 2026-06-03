<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\PayrollItem;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('karyawan')) {
            $employee = $user->employee;
            if (!$employee) return redirect()->route('dashboard');
            $payrolls = Payroll::where('employee_id', $employee->id)
                ->orderByDesc('period_year')->orderByDesc('period_month')->paginate(12);
            return view('payroll.own', compact('payrolls', 'employee'));
        }

        $query = Payroll::with('employee.department');

        if ($request->month) $query->where('period_month', $request->month);
        if ($request->year) $query->where('period_year', $request->year);
        if ($request->employee_id) $query->where('employee_id', $request->employee_id);
        if ($request->status) $query->where('status', $request->status);

        $payrolls = $query->orderByDesc('period_year')->orderByDesc('period_month')
            ->orderBy('created_at')->paginate(20)->withQueryString();

        $employees = Employee::active()->orderBy('name')->get();
        $currentMonth = date('m');
        $currentYear = date('Y');

        return view('payroll.index', compact('payrolls', 'employees', 'currentMonth', 'currentYear'));
    }

    public function create()
    {
        $employees = Employee::active()->with('position')->orderBy('name')->get();
        return view('payroll.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_month' => 'required|integer|between:1,12',
            'period_year' => 'required|integer|min:2000',
            'basic_salary' => 'required|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.type' => 'required|in:allowance,deduction,bonus',
            'items.*.name' => 'required|string',
            'items.*.amount' => 'required|numeric|min:0',
        ]);

        // Check if payroll already exists
        $exists = Payroll::where('employee_id', $validated['employee_id'])
            ->where('period_month', $validated['period_month'])
            ->where('period_year', $validated['period_year'])->exists();

        if ($exists) {
            return back()->with('error', 'Penggajian untuk periode ini sudah ada.')->withInput();
        }

        $items = $request->input('items', []);
        $totalAllowance = 0;
        $totalDeduction = 0;
        $totalBonus = 0;

        foreach ($items as $item) {
            if ($item['type'] === 'allowance') $totalAllowance += $item['amount'];
            elseif ($item['type'] === 'deduction') $totalDeduction += $item['amount'];
            elseif ($item['type'] === 'bonus') $totalBonus += $item['amount'];
        }

        $grossSalary = $validated['basic_salary'] + $totalAllowance + ($validated['overtime_pay'] ?? 0) + $totalBonus;
        $netSalary = $grossSalary - $totalDeduction;

        $payroll = Payroll::create([
            'employee_id' => $validated['employee_id'],
            'period_month' => $validated['period_month'],
            'period_year' => $validated['period_year'],
            'basic_salary' => $validated['basic_salary'],
            'total_allowance' => $totalAllowance,
            'total_deduction' => $totalDeduction,
            'overtime_pay' => $validated['overtime_pay'] ?? 0,
            'bonus' => $totalBonus,
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
            'notes' => $validated['notes'] ?? null,
            'created_by' => Auth::id(),
        ]);

        foreach ($items as $item) {
            PayrollItem::create([
                'payroll_id' => $payroll->id,
                'type' => $item['type'],
                'name' => $item['name'],
                'amount' => $item['amount'],
            ]);
        }

        return redirect()->route('payroll.show', $payroll)
            ->with('success', 'Data penggajian berhasil dibuat.');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load(['employee.department', 'employee.position', 'items', 'creator']);
        $company = \App\Models\CompanySetting::all()->keyBy('key');
        return view('payroll.show', compact('payroll', 'company'));
    }

    public function approve(Payroll $payroll)
    {
        $payroll->update(['status' => 'approved']);
        return back()->with('success', 'Penggajian berhasil disetujui.');
    }

    public function markPaid(Payroll $payroll)
    {
        $payroll->update(['status' => 'paid']);

        // Notify employee
        if ($payroll->employee->user) {
            \App\Models\HrNotification::create([
                'user_id' => $payroll->employee->user->id,
                'type' => 'payroll_paid',
                'title' => 'Gaji Sudah Dibayar',
                'message' => "Gaji Anda periode {$payroll->period_label} telah dibayarkan",
            ]);
        }

        return back()->with('success', 'Status penggajian berhasil diperbarui menjadi Sudah Dibayar.');
    }

    public function slip(Payroll $payroll)
    {
        $user = Auth::user();
        // Karyawan can only view their own slip
        if ($user->hasRole('karyawan') && $user->employee) {
            if ($payroll->employee_id !== $user->employee->id) {
                abort(403);
            }
        }

        $payroll->load(['employee.department', 'employee.position', 'items']);
        $company = \App\Models\CompanySetting::all()->keyBy('key');
        return view('payroll.slip', compact('payroll', 'company'));
    }

    public function downloadSlip(Payroll $payroll)
    {
        $user = Auth::user();
        if ($user->hasRole('karyawan') && $user->employee) {
            if ($payroll->employee_id !== $user->employee->id) abort(403);
        }

        $payroll->load(['employee.department', 'employee.position', 'items']);
        $company = \App\Models\CompanySetting::all()->keyBy('key');
        $pdf = Pdf::loadView('payroll.slip-pdf', compact('payroll', 'company'));

        return $pdf->download("slip-gaji-{$payroll->employee->nik}-{$payroll->period_year}-{$payroll->period_month}.pdf");
    }

    public function destroy(Payroll $payroll)
    {
        if ($payroll->status !== 'draft') {
            return back()->with('error', 'Hanya penggajian berstatus draft yang dapat dihapus.');
        }
        $payroll->items()->delete();
        $payroll->delete();
        return redirect()->route('payroll.index')->with('success', 'Data penggajian berhasil dihapus.');
    }
}
