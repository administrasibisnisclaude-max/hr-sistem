@extends('layouts.app')

@section('title', 'Profil Saya')

@section('breadcrumb')
    <li class="breadcrumb-item active">Profil Saya</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0"><i class="fas fa-user me-2 text-primary"></i>Profil Saya</h4>
    <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm"><i class="fas fa-edit me-2"></i>Edit Profil</a>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center py-4">
                @if($user->employee?->photo)
                    <img src="{{ Storage::url($user->employee->photo) }}" class="rounded-circle mb-3" style="width:100px;height:100px;object-fit:cover;border:3px solid #e0e4ec;">
                @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width:100px;height:100px;font-size:2rem;font-weight:700;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <h5 class="fw-bold">{{ $user->name }}</h5>
                <p class="text-muted small">{{ $user->email }}</p>
                @foreach($user->getRoleNames() as $role)
                    @php $roleColors = ['owner' => '6f42c1', 'admin' => '0d6efd', 'karyawan' => '198754']; @endphp
                    <span class="badge px-3 py-2 mb-2" style="background:#{{ $roleColors[$role] ?? '6c757d' }}">{{ ucfirst($role) }}</span>
                @endforeach
                @if($user->employee)
                    <div class="mt-3 text-start border-top pt-3">
                        <small class="text-muted d-block mb-1"><strong>NIK:</strong> {{ $user->employee->nik }}</small>
                        <small class="text-muted d-block mb-1"><strong>Jabatan:</strong> {{ $user->employee->position?->name ?? '-' }}</small>
                        <small class="text-muted d-block"><strong>Departemen:</strong> {{ $user->employee->department?->name ?? '-' }}</small>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-user me-2"></i>Informasi Pribadi</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Nama Lengkap</small>
                        <strong>{{ $user->name }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Email</small>
                        <strong>{{ $user->email }}</strong>
                    </div>
                    @if($user->employee)
                        <div class="col-md-6">
                            <small class="text-muted d-block">No. Telepon</small>
                            <strong>{{ $user->employee->phone ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Jenis Kelamin</small>
                            <strong>{{ $user->employee->gender_label ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Tanggal Lahir</small>
                            <strong>{{ $user->employee->birth_date?->translatedFormat('d F Y') ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Tanggal Masuk</small>
                            <strong>{{ $user->employee->hire_date?->translatedFormat('d F Y') ?? '-' }}</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Alamat</small>
                            <strong>{{ $user->employee->address ?? '-' }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($user->employee)
            <div class="card">
                <div class="card-header"><i class="fas fa-briefcase me-2"></i>Informasi Kepegawaian</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Jabatan</small>
                            <strong>{{ $user->employee->position?->name ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Departemen</small>
                            <strong>{{ $user->employee->department?->name ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Status Kepegawaian</small>
                            <strong>{{ $user->employee->status_label ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Bank / Rekening</small>
                            <strong>{{ $user->employee->bank_name ?? '-' }} / {{ $user->employee->bank_account ?? '-' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
