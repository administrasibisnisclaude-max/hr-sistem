@extends('layouts.app')
@section('title', 'Edit Pengumuman')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-bullhorn me-2 text-primary"></i>Edit Pengumuman</h1>
    <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>
<div class="card" style="max-width:700px;">
    <div class="card-body">
        <form action="{{ route('announcements.update', $announcement) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $announcement->title) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Isi Pengumuman *</label>
                <textarea name="content" class="form-control" rows="6" required>{{ old('content', $announcement->content) }}</textarea>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Target *</label>
                    <select name="target_role" class="form-select" required>
                        @foreach(['all' => 'Semua', 'karyawan' => 'Karyawan', 'admin' => 'Admin', 'owner' => 'Owner'] as $val => $label)
                            <option value="{{ $val }}" {{ old('target_role', $announcement->target_role) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Kedaluarsa</label>
                    <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at', $announcement->expires_at?->format('Y-m-d')) }}">
                </div>
            </div>
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ $announcement->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">Aktif</label>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
                <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
