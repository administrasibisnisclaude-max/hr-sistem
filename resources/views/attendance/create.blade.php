@extends('layouts.app')
@section('title', 'Tambah Absensi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Absensi</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection
@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>
<div class="card mb-4" style="max-width:600px;">
    <div class="card-header"><i class="fas fa-clock me-2"></i>Tambah/Edit Absensi</div>
    <div class="card-body">
        <form action="{{ route('attendance.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Karyawan *</label>
                <select name="employee_id" class="form-select" required>
                    <option value="">Pilih Karyawan...</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }} ({{ $emp->nik }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal *</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jam Masuk</label>
                    <input type="time" name="clock_in" class="form-control" value="{{ old('clock_in') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jam Pulang</label>
                    <input type="time" name="clock_out" class="form-control" value="{{ old('clock_out') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Status *</label>
                <select name="status" class="form-select" required>
                    <option value="hadir" {{ old('status') === 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="izin" {{ old('status') === 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ old('status') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="alpha" {{ old('status') === 'alpha' ? 'selected' : '' }}>Alpha</option>
                    <option value="cuti" {{ old('status') === 'cuti' ? 'selected' : '' }}>Cuti</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Jam Lembur</label>
                <input type="number" name="overtime_hours" class="form-control" step="0.5" min="0" max="24" value="{{ old('overtime_hours', 0) }}">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Catatan</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
                <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
