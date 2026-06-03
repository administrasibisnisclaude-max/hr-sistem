@extends('layouts.app')
@section('title', 'Tambah Pengajuan Cuti')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-calendar-plus me-2 text-primary"></i>Tambah Pengajuan Cuti</h1>
    <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>
<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form action="{{ route('leaves.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Karyawan *</label>
                <select name="employee_id" class="form-select" required>
                    <option value="">Pilih Karyawan...</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Jenis Cuti *</label>
                <select name="leave_type" class="form-select" required>
                    <option value="tahunan">Cuti Tahunan</option>
                    <option value="sakit">Cuti Sakit</option>
                    <option value="melahirkan">Cuti Melahirkan</option>
                    <option value="darurat">Cuti Darurat</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Mulai *</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Selesai *</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Alasan *</label>
                <textarea name="reason" class="form-control" rows="3" required>{{ old('reason') }}</textarea>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Lampiran (opsional)</label>
                <input type="file" name="attachment" class="form-control">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
                <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
