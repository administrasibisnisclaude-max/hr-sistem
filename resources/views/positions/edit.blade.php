@extends('layouts.app')
@section('title', 'Edit Jabatan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('positions.index') }}">Jabatan</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('positions.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>
<div class="card mb-4" style="max-width:600px;">
    <div class="card-header"><i class="fas fa-briefcase me-2"></i>Edit Jabatan</div>
    <div class="card-body">
        <form action="{{ route('positions.update', $position) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Departemen *</label>
                <select name="department_id" class="form-select" required>
                    <option value="">Pilih Departemen...</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $position->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Jabatan *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $position->name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Gaji Pokok *</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="basic_salary" class="form-control" value="{{ old('basic_salary', $position->basic_salary) }}" min="0" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $position->description) }}</textarea>
            </div>
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ $position->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">Jabatan Aktif</label>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
                <a href="{{ route('positions.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
