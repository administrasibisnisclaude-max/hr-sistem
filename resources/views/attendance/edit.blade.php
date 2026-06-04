@extends('layouts.app')
@section('title', 'Edit Absensi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Absensi</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>
<div class="card mb-4" style="max-width:600px;">
    <div class="card-header"><i class="fas fa-clock me-2"></i>Edit Absensi</div>
    <div class="card-body">
        <div class="mb-3 p-3 bg-light rounded">
            <strong>{{ $attendance->employee->name }}</strong> - {{ $attendance->date->translatedFormat('l, d F Y') }}
        </div>
        <form action="{{ route('attendance.update', $attendance) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jam Masuk</label>
                    <input type="time" name="clock_in" class="form-control" value="{{ old('clock_in', $attendance->clock_in) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jam Pulang</label>
                    <input type="time" name="clock_out" class="form-control" value="{{ old('clock_out', $attendance->clock_out) }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Status *</label>
                <select name="status" class="form-select" required>
                    @foreach(['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpha' => 'Alpha', 'cuti' => 'Cuti'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $attendance->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Jam Lembur</label>
                <input type="number" name="overtime_hours" class="form-control" step="0.5" min="0" max="24" value="{{ old('overtime_hours', $attendance->overtime_hours) }}">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Catatan</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes', $attendance->notes) }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
                <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
