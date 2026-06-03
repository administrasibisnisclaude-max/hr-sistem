<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $query = Contract::with('employee.department');

        if ($request->status) $query->where('status', $request->status);
        if ($request->employee_id) $query->where('employee_id', $request->employee_id);

        // Expiring soon filter
        if ($request->expiring) {
            $query->where('status', 'active')
                ->whereNotNull('end_date')
                ->whereBetween('end_date', [Carbon::today(), Carbon::today()->addDays(30)]);
        }

        $contracts = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $employees = Employee::active()->orderBy('name')->get();

        return view('contracts.index', compact('contracts', 'employees'));
    }

    public function create()
    {
        $employees = Employee::active()->orderBy('name')->get();
        return view('contracts.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'contract_type' => 'required|in:tetap,kontrak,magang,percobaan',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:active,expired,terminated',
            'notes' => 'nullable|string',
            'file' => 'nullable|file|max:5120',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('contracts', 'public');
        }

        Contract::create($validated);
        activity()->causedBy(auth()->user())->log("Kontrak karyawan ID {$validated['employee_id']} ditambahkan");

        return redirect()->route('contracts.index')
            ->with('success', 'Kontrak berhasil ditambahkan.');
    }

    public function edit(Contract $contract)
    {
        $employees = Employee::active()->orderBy('name')->get();
        return view('contracts.edit', compact('contract', 'employees'));
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'contract_type' => 'required|in:tetap,kontrak,magang,percobaan',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:active,expired,terminated',
            'notes' => 'nullable|string',
            'file' => 'nullable|file|max:5120',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('contracts', 'public');
        }

        $contract->update($validated);

        return redirect()->route('contracts.index')
            ->with('success', 'Kontrak berhasil diperbarui.');
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();
        return redirect()->route('contracts.index')->with('success', 'Kontrak berhasil dihapus.');
    }
}
