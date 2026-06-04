@extends('layouts.app')
@section('title', 'Edit Kontrak')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('contracts.index') }}">Kontrak</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>
<div class="card mb-4" style="max-width:600px;">
    <div class="card-header"><i class="fas fa-file-contract me-2"></i>Edit Kontrak Kerja</div>
    <div class="card-body">
        <div class="mb-3 p-3 bg-light rounded">Karyawan: <strong>{{ $contract->employee->name }}</strong></div>
        <form action="{{ route('contracts.update', $contract) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Tipe Kontrak *</label>
                <select name="contract_type" class="form-select" required>
                    @foreach(['tetap' => 'Tetap', 'kontrak' => 'Kontrak', 'magang' => 'Magang', 'percobaan' => 'Percobaan'] as $val => $label)
                        <option value="{{ $val }}" {{ old('contract_type', $contract->contract_type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Mulai *</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $contract->start_date->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Berakhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $contract->end_date?->format('Y-m-d')) }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Status *</label>
                <select name="status" class="form-select" required>
                    @foreach(['active' => 'Aktif', 'expired' => 'Kadaluarsa', 'terminated' => 'Berakhir'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $contract->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Catatan</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $contract->notes) }}</textarea>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">File Kontrak</label>
                <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx">
                @if($contract->file_path)<small class="text-muted">File sudah ada. Upload baru untuk mengganti.</small>@endif
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
                <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
