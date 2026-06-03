<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'position']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('nik', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->status) {
            $query->where('employment_status', $request->status);
        }

        $employees = $query->orderBy('name')->paginate(15)->withQueryString();
        $departments = Department::where('is_active', true)->get();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        $positions = Position::where('is_active', true)->get();
        return view('employees.create', compact('departments', 'positions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|unique:employees,nik',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'hire_date' => 'nullable|date',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'employment_status' => 'required|in:tetap,kontrak,magang,tidak_aktif',
            'bank_name' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'npwp' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'create_user' => 'nullable|boolean',
            'password' => 'nullable|string|min:8',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $employee = Employee::create($validated);

        // Initialize leave balance
        LeaveBalance::create([
            'employee_id' => $employee->id,
            'year' => date('Y'),
            'total_days' => 12,
            'used_days' => 0,
        ]);

        // Create user account if requested
        if ($request->boolean('create_user')) {
            $user = User::create([
                'name' => $employee->name,
                'email' => $employee->email,
                'password' => Hash::make($request->password ?? 'password'),
                'employee_id' => $employee->id,
                'is_active' => true,
            ]);
            $user->assignRole('karyawan');
        }

        activity()->causedBy(auth()->user())->performedOn($employee)->log('Karyawan baru ditambahkan');

        return redirect()->route('employees.index')
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['department', 'position', 'contracts', 'documents', 'user']);
        $leaveBalance = $employee->leaveBalances()->where('year', date('Y'))->first();
        $recentAttendances = $employee->attendances()->orderByDesc('date')->take(10)->get();
        $recentLeaves = $employee->leaveRequests()->orderByDesc('created_at')->take(5)->get();
        $latestPayroll = $employee->payrolls()->orderByDesc('period_year')->orderByDesc('period_month')->first();
        $performances = $employee->performances()->with('evaluator')->orderByDesc('created_at')->take(5)->get();
        return view('employees.show', compact('employee', 'leaveBalance', 'recentAttendances', 'recentLeaves', 'latestPayroll', 'performances'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::where('is_active', true)->get();
        $positions = Position::where('is_active', true)->get();
        return view('employees.edit', compact('employee', 'departments', 'positions'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'nik' => 'required|unique:employees,nik,' . $employee->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'hire_date' => 'nullable|date',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'employment_status' => 'required|in:tetap,kontrak,magang,tidak_aktif',
            'bank_name' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'npwp' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $employee->update($validated);
        activity()->causedBy(auth()->user())->performedOn($employee)->log('Data karyawan diperbarui');

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        activity()->causedBy(auth()->user())->log("Karyawan {$employee->name} dihapus");
        return redirect()->route('employees.index')
            ->with('success', 'Karyawan berhasil dihapus.');
    }
}
