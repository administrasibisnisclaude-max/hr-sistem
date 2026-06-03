@extends('layouts.app')
@section('title', $announcement->title)
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-bullhorn me-2 text-primary"></i>Pengumuman</h1>
    <a href="{{ auth()->user()->hasRole('karyawan') ? route('announcements.own') : route('announcements.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>
<div class="card" style="max-width:700px;">
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
