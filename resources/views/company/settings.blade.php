@extends('layouts.app')

@section('title', 'Pengaturan Perusahaan')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pengaturan Perusahaan</li>
@endsection

@section('content')
<div class="mb-4">
    <h4 class="mb-0"><i class="fas fa-cog me-2 text-primary"></i>Pengaturan Perusahaan</h4>
</div>

<div class="card" style="max-width:700px;">
    <div class="card-header"><i class="fas fa-building me-2"></i>Konfigurasi Sistem HR</div>
    <div class="card-body">
        <form action="{{ route('company.update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-building me-2"></i>Informasi Perusahaan</h6>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Perusahaan <span class="text-danger">*</span></label>
                <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $settings->get('company_name')?->value) }}" required>
                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Alamat</label>
                <textarea name="company_address" class="form-control" rows="2">{{ old('company_address', $settings->get('company_address')?->value) }}</textarea>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Telepon</label>
                    <input type="text" name="company_phone" class="form-control" value="{{ old('company_phone', $settings->get('company_phone')?->value) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="company_email" class="form-control" value="{{ old('company_email', $settings->get('company_email')?->value) }}">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Logo Perusahaan</label>
                <input type="file" name="logo" class="form-control" accept="image/*">
                @if($settings->get('company_logo')?->value)
                    <div class="mt-2 d-flex align-items-center gap-2">
                        <img src="{{ Storage::url($settings->get('company_logo')->value) }}" alt="Logo" style="height:60px;">
                        <small class="text-muted">Logo saat ini</small>
                    </div>
                @endif
            </div>

            <hr>
            <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-clock me-2"></i>Pengaturan Jam Kerja</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jam Masuk</label>
                    <input type="time" name="work_start_time" class="form-control" value="{{ old('work_start_time', $settings->get('work_start_time')?->value ?? '08:00') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jam Pulang</label>
                    <input type="time" name="work_end_time" class="form-control" value="{{ old('work_end_time', $settings->get('work_end_time')?->value ?? '17:00') }}">
                </div>
            </div>

            <hr>
            <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-calendar me-2"></i>Pengaturan Cuti &amp; Gaji</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jatah Cuti Per Tahun (hari)</label>
                    <input type="number" name="leave_days_per_year" class="form-control" value="{{ old('leave_days_per_year', $settings->get('leave_days_per_year')?->value ?? 12) }}" min="1">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tarif Lembur (x gaji pokok)</label>
                    <input type="number" name="overtime_rate" class="form-control" step="0.1" value="{{ old('overtime_rate', $settings->get('overtime_rate')?->value ?? 1.5) }}" min="1">
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Simpan Pengaturan</button>
            </div>
        </form>
    </div>
</div>
@endsection
