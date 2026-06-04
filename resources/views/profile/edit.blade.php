@extends('layouts.app')

@section('title', 'Edit Profil')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">Profil Saya</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0"><i class="fas fa-user-edit me-2 text-primary"></i>Edit Profil</h4>
    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-header"><i class="fas fa-edit me-2"></i>Form Edit Profil</div>
    <div class="card-body">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" class="form-control" value="{{ $user->email }}" readonly disabled>
                <div class="form-text">Email tidak dapat diubah.</div>
            </div>

            @if($user->employee)
                <div class="mb-3">
                    <label class="form-label fw-semibold">No. Telepon</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->employee->phone) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $user->employee->address) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Foto Profil</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                    @if($user->employee->photo)
                        <div class="mt-2 d-flex align-items-center gap-2">
                            <img src="{{ Storage::url($user->employee->photo) }}" class="rounded-circle" style="width:50px;height:50px;object-fit:cover;">
                            <small class="text-muted">Foto saat ini</small>
                        </div>
                    @endif
                </div>
            @endif

            <hr>
            <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-lock me-2"></i>Ubah Password</h6>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password Saat Ini</label>
                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password Baru</label>
                <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" class="form-control">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
