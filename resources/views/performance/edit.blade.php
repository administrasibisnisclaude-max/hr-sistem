@extends('layouts.app')
@section('title', 'Edit Evaluasi Kinerja')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-chart-bar me-2 text-primary"></i>Edit Evaluasi Kinerja</h1>
    <a href="{{ route('performance.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>
<div class="card" style="max-width:700px;">
    <div class="card-body">
        <form action="{{ route('performance.update', $performance) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Karyawan *</label>
                    <select name="employee_id" class="form-select" required>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id', $performance->employee_id) == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Periode *</label>
                    <input type="text" name="period" class="form-control" value="{{ old('period', $performance->period) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Skor (0-100) *</label>
                    <input type="number" name="score" class="form-control" min="0" max="100" step="0.01" value="{{ old('score', $performance->score) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Catatan</label>
                    <textarea name="notes" class="form-control" rows="4">{{ old('notes', $performance->notes) }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
                <a href="{{ route('performance.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
