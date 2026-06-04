@extends('layouts.app')

@section('title', 'Buat Pengumuman')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('announcements.index') }}">Pengumuman</a></li>
    <li class="breadcrumb-item active">Buat</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0"><i class="fas fa-bullhorn me-2 text-warning"></i>Buat Pengumuman</h4>
    <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="card" style="max-width:700px;">
    <div class="card-header"><i class="fas fa-edit me-2"></i>Form Pengumuman</div>
    <div class="card-body">
        <form action="{{ route('announcements.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Isi Pengumuman <span class="text-danger">*</span></label>
                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="6" required>{{ old('content') }}</textarea>
                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Target <span class="text-danger">*</span></label>
                    <select name="target_role" class="form-select @error('target_role') is-invalid @enderror" required>
                        <option value="all" {{ old('target_role') === 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="karyawan" {{ old('target_role') === 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                        <option value="admin" {{ old('target_role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="owner" {{ old('target_role') === 'owner' ? 'selected' : '' }}>Owner</option>
                    </select>
                    @error('target_role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Kedaluarsa</label>
                    <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                </div>
            </div>
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">Publikasikan Sekarang</label>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-2"></i>Publikasikan</button>
                <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
