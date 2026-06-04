@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Karyawan</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <span class="text-muted">Isi data karyawan baru</span>
    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-user me-2"></i>Data Pribadi</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIK <span class="text-danger">*</span></label>
                            <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}" required>
                            @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. Telepon</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis Kelamin</label>
                            <select name="gender" class="form-select">
                                <option value="">Pilih...</option>
                                <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NPWP</label>
                            <input type="text" name="npwp" class="form-control" value="{{ old('npwp') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kontak Darurat</label>
                            <input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact') }}" placeholder="Nama - No. HP">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-briefcase me-2"></i>Data Kepegawaian</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Masuk</label>
                            <input type="date" name="hire_date" class="form-control" value="{{ old('hire_date') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status Kepegawaian <span class="text-danger">*</span></label>
                            <select name="employment_status" class="form-select" required>
                                <option value="tetap" {{ old('employment_status') === 'tetap' ? 'selected' : '' }}>Karyawan Tetap</option>
                                <option value="kontrak" {{ old('employment_status') === 'kontrak' ? 'selected' : '' }}>Karyawan Kontrak</option>
                                <option value="magang" {{ old('employment_status') === 'magang' ? 'selected' : '' }}>Magang</option>
                                <option value="tidak_aktif" {{ old('employment_status') === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Departemen</label>
                            <select name="department_id" class="form-select">
                                <option value="">Pilih Departemen...</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jabatan</label>
                            <select name="position_id" class="form-select">
                                <option value="">Pilih Jabatan...</option>
                                @foreach($positions as $pos)
                                    <option value="{{ $pos->id }}" {{ old('position_id') == $pos->id ? 'selected' : '' }}>{{ $pos->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Bank</label>
                            <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. Rekening</label>
                            <input type="text" name="bank_account" class="form-control" value="{{ old('bank_account') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-key me-2"></i>Akun Login (Opsional)</div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="create_user" id="createUser" value="1" {{ old('create_user') ? 'checked' : '' }}>
                        <label class="form-check-label" for="createUser">Buat akun login untuk karyawan ini</label>
                    </div>
                    <div id="passwordField" style="display:none;">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter (default: password)">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-camera me-2"></i>Foto Karyawan</div>
                <div class="card-body text-center">
                    <div id="photoPreview" class="mb-3" style="height:200px; background:#f8f9fa; border-radius:8px; display:flex; align-items:center; justify-content:center; overflow:hidden;">
                        <i class="fas fa-user fa-4x text-muted"></i>
                    </div>
                    <input type="file" name="photo" class="form-control" id="photoInput" accept="image/*">
                    <small class="text-muted">JPG/PNG, maks 2MB</small>
                </div>
            </div>
        </div>

        <div class="col-12 d-flex justify-content-end gap-2">
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-2"></i>Simpan Karyawan
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('photoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').innerHTML = `<img src="${e.target.result}" style="width:100%;height:200px;object-fit:cover;">`;
        };
        reader.readAsDataURL(file);
    }
});
document.getElementById('createUser').addEventListener('change', function() {
    document.getElementById('passwordField').style.display = this.checked ? 'block' : 'none';
});
</script>
@endpush
