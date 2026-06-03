<?php

namespace App\Http\Controllers;

use App\Models\PerformanceEvaluation;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceController extends Controller
{
    public function index(Request $request)
    {
        $query = PerformanceEvaluation::with(['employee.department', 'evaluator']);

        if ($request->employee_id) $query->where('employee_id', $request->employee_id);
        if ($request->period) $query->where('period', 'like', "%{$request->period}%");

        $evaluations = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $employees = Employee::active()->orderBy('name')->get();

        return view('performance.index', compact('evaluations', 'employees'));
    }

    public function create()
    {
        $employees = Employee::active()->orderBy('name')->get();
        return view('performance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period' => 'required|string|max:50',
            'score' => 'required|numeric|between:0,100',
            'notes' => 'nullable|string',
            'criteria' => 'nullable|array',
        ]);

        // Auto-calculate grade
        $grade = 'E';
        if ($validated['score'] >= 90) $grade = 'A';
        elseif ($validated['score'] >= 80) $grade = 'B';
        elseif ($validated['score'] >= 70) $grade = 'C';
        elseif ($validated['score'] >= 60) $grade = 'D';

        PerformanceEvaluation::create(array_merge($validated, [
            'evaluator_id' => Auth::id(),
            'grade' => $grade,
        ]));

        return redirect()->route('performance.index')
            ->with('success', 'Evaluasi kinerja berhasil disimpan.');
    }

    public function show(PerformanceEvaluation $performance)
    {
        $performance->load(['employee.department', 'evaluator']);
        return view('performance.show', compact('performance'));
    }

    public function edit(PerformanceEvaluation $performance)
    {
        $employees = Employee::active()->orderBy('name')->get();
        return view('performance.edit', compact('performance', 'employees'));
    }

    public function update(Request $request, PerformanceEvaluation $performance)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period' => 'required|string|max:50',
            'score' => 'required|numeric|between:0,100',
            'notes' => 'nullable|string',
        ]);

        $grade = 'E';
        if ($validated['score'] >= 90) $grade = 'A';
        elseif ($validated['score'] >= 80) $grade = 'B';
        elseif ($validated['score'] >= 70) $grade = 'C';
        elseif ($validated['score'] >= 60) $grade = 'D';

        $performance->update(array_merge($validated, ['grade' => $grade]));

        return redirect()->route('performance.index')
            ->with('success', 'Evaluasi kinerja berhasil diperbarui.');
    }

    public function destroy(PerformanceEvaluation $performance)
    {
        $performance->delete();
        return redirect()->route('performance.index')->with('success', 'Evaluasi kinerja berhasil dihapus.');
    }
}
