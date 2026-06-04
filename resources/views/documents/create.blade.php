@extends('layouts.app')
@section('title', 'Upload Dokumen')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Dokumen</a></li>
    <li class="breadcrumb-item active">Upload</li>
@endsection
@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>
<div class="card mb-4" style="max-width:600px;">
    <div class="card-header"><i class="fas fa-upload me-2"></i>Upload Dokumen Karyawan</div>
    <div class="card-body">
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
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
                <label class="form-label fw-semibold">Jenis Dokumen *</label>
                <select name="document_type" class="form-select" required>
                    @foreach(['ktp' => 'KTP', 'npwp' => 'NPWP', 'ijazah' => 'Ijazah', 'cv' => 'CV', 'foto' => 'Foto', 'kontrak' => 'Kontrak', 'lainnya' => 'Lainnya'] as $val => $label)
                        <option value="{{ $val }}" {{ old('document_type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">File *</label>
                <input type="file" name="file" class="form-control" required>
                <small class="text-muted">Maks 10MB. Format: PDF, JPG, PNG, DOC, DOCX</small>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Catatan</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-2"></i>Upload</button>
                <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
