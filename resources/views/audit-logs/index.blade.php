@extends('layouts.app')

@section('title', 'Audit Log')

@section('breadcrumb')
    <li class="breadcrumb-item active">Audit Log</li>
@endsection

@section('content')
<div class="mb-4">
    <h4 class="mb-0"><i class="fas fa-history me-2 text-primary"></i>Audit Log</h4>
</div>

<div class="card mb-3">
    <div class="card-header"><i class="fas fa-filter me-2"></i>Filter Log</div>
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari aktivitas..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="causer_id" class="form-select">
                    <option value="">Semua Pengguna</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('causer_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Filter</button>
                <a href="{{ route('audit-logs.index') }}" class="btn btn-outline-secondary ms-1">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Pengguna</th>
                        <th>Aktivitas</th>
                        <th>Model</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="small text-muted">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <div class="fw-semibold">{{ $log->causer?->name ?? 'System' }}</div>
                                <small class="text-muted">{{ $log->causer?->email }}</small>
                            </td>
                            <td>{{ $log->description }}</td>
                            <td class="small text-muted">
                                {{ $log->subject_type ? class_basename($log->subject_type) : '-' }}
                                @if($log->subject_id) #{{ $log->subject_id }}@endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada log aktivitas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="p-3">{{ $logs->links() }}</div>
        @endif
    </div>
</div>
@endsection
