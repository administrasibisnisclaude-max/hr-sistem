@extends('layouts.app')
@section('title', 'Pengumuman')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-bullhorn me-2 text-primary"></i>Pengumuman</h1>
    @can('manage announcements')
        <a href="{{ route('announcements.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Buat Pengumuman</a>
    @endcan
</div>
<div class="row g-3">
    @forelse($announcements as $announcement)
        <div class="col-12">
            <div class="card {{ !$announcement->is_active ? 'opacity-75' : '' }}">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h5 class="mb-0 fw-bold">{{ $announcement->title }}</h5>
                                @if(!$announcement->is_active)<span class="badge bg-secondary">Nonaktif</span>@endif
                                <span class="badge bg-info">{{ $announcement->target_role === 'all' ? 'Semua' : ucfirst($announcement->target_role) }}</span>
                            </div>
                            <div class="text-muted mb-3">{{ $announcement->content }}</div>
                            <small class="text-muted">
                                <i class="fas fa-user me-1"></i>{{ $announcement->admin->name }}
                                <span class="ms-3"><i class="fas fa-clock me-1"></i>{{ $announcement->created_at->diffForHumans() }}</span>
                                @if($announcement->expires_at)
                                    <span class="ms-3 text-warning"><i class="fas fa-calendar-times me-1"></i>Berakhir: {{ $announcement->expires_at->format('d/m/Y') }}</span>
                                @endif
                            </small>
                        </div>
                        @can('manage announcements')
                            <div class="btn-group btn-group-sm ms-3">
                                <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengumuman ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5 text-muted">
                    <i class="fas fa-bullhorn fa-3x mb-3 opacity-25"></i>
                    <p>Belum ada pengumuman</p>
                </div>
            </div>
        </div>
    @endforelse
</div>
@if($announcements->hasPages())<div class="mt-3">{{ $announcements->links() }}</div>@endif
@endsection
