@extends('layouts.app')

@section('title', $announcement->title)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ auth()->user()->hasRole('karyawan') ? route('announcements.index') : route('announcements.index') }}">Pengumuman</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0"><i class="fas fa-bullhorn me-2 text-warning"></i>Pengumuman</h4>
    <a href="{{ auth()->user()->hasRole('karyawan') ? route('announcements.index') : route('announcements.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="card" style="max-width:700px;">
    <div class="card-header"><i class="fas fa-bullhorn me-2"></i>Detail Pengumuman</div>
    <div class="card-body p-4">
        <h3 class="fw-bold mb-3">{{ $announcement->title }}</h3>
        <div class="text-muted small mb-4">
            <i class="fas fa-user me-1"></i>{{ $announcement->admin->name }}
            <span class="ms-3"><i class="fas fa-clock me-1"></i>{{ $announcement->created_at->translatedFormat('d F Y, H:i') }}</span>
            <span class="ms-3 badge bg-info">{{ $announcement->target_role === 'all' ? 'Semua' : ucfirst($announcement->target_role) }}</span>
        </div>
        <div class="lh-lg">{{ $announcement->content }}</div>
    </div>
</div>
@endsection
