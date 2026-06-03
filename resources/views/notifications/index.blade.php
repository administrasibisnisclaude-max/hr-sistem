@extends('layouts.app')
@section('title', 'Notifikasi')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-bell me-2 text-primary"></i>Notifikasi</h1>
    <form action="{{ route('notifications.read-all') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-secondary btn-sm"><i class="fas fa-check-double me-2"></i>Tandai Semua Dibaca</button>
    </form>
</div>
<div class="card">
    <div class="list-group list-group-flush">
        @forelse($notifications as $notif)
            <div class="list-group-item list-group-item-action {{ !$notif->is_read ? 'bg-primary bg-opacity-5' : '' }}">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="d-flex align-items-start gap-3">
                        <div class="mt-1">
                            <div class="rounded-circle {{ !$notif->is_read ? 'bg-primary' : 'bg-secondary bg-opacity-25' }} d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                <i class="fas fa-{{ !$notif->is_read ? 'bell text-white' : 'bell-slash text-muted' }} small"></i>
                            </div>
                        </div>
                        <div>
                            <div class="fw-semibold {{ !$notif->is_read ? 'text-primary' : '' }}">{{ $notif->title }}</div>
                            <div class="text-muted">{{ $notif->message }}</div>
                            <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2 ms-3">
                        @if(!$notif->is_read)
                            <form action="{{ route('notifications.read', $notif) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Tandai dibaca"><i class="fas fa-check"></i></button>
                            </form>
                        @endif
                        <form action="{{ route('notifications.destroy', $notif) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="list-group-item text-center text-muted py-5">
                <i class="fas fa-bell fa-3x mb-3 opacity-25"></i>
                <p class="mb-0">Tidak ada notifikasi</p>
            </div>
        @endforelse
    </div>
    @if($notifications->hasPages())<div class="p-3">{{ $notifications->links() }}</div>@endif
</div>
@endsection
