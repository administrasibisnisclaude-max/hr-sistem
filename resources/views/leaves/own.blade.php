@extends('layouts.app')
@section('title', 'Cuti Saya')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-calendar-times me-2 text-primary"></i>Cuti Saya</h1>
    <a href="{{ route('leaves.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Ajukan Cuti</a>
</div>

@if($leaveBalance)
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3"><i class="fas fa-balance-scale me-2 text-primary"></i>Saldo Cuti {{ date('Y') }}</h6>
            <div class="row text-center g-3">
                <div class="col-4">
                    <div class="p-3 bg-primary bg-opacity-10 rounded">
                        <div class="fs-2 fw-bold text-primary">{{ $leaveBalance->total_days }}</div>
                        <small class="text-muted">Total Jatah</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 bg-warning bg-opacity-10 rounded">
                        <div class="fs-2 fw-bold text-warning">{{ $leaveBalance->used_days }}</div>
                        <small class="text-muted">Digunakan</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 bg-success bg-opacity-10 rounded">
                        <div class="fs-2 fw-bold text-success">{{ $leaveBalance->remaining_days }}</div>
                        <small class="text-muted">Sisa</small>
                    </div>
                </div>
            </div>
            <div class="progress mt-3" style="height:8px;">
                <div class="progress-bar bg-primary" style="width:{{ $leaveBalance->total_days > 0 ? ($leaveBalance->used_days / $leaveBalance->total_days * 100) : 0 }}%"></div>
            </div>
        </div>
    </div>
@endif

<div class="card">
    <div class="card-header">Riwayat Pengajuan Cuti</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Jenis</th><th>Tanggal</th><th>Hari</th><th>Alasan</th><th>Status</th><th>Catatan Approval</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($leaves as $leave)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $leave->leave_type_label }}</span></td>
                        <td class="small">{{ $leave->start_date->format('d/m/Y') }}<br>{{ $leave->end_date->format('d/m/Y') }}</td>
                        <td class="text-center fw-bold">{{ $leave->days }}</td>
                        <td class="small">{{ Str::limit($leave->reason, 50) }}</td>
                        <td>{!! $leave->status_badge !!}</td>
                        <td class="small text-muted">{{ $leave->notes ?? '-' }}</td>
                        <td>
                            @if($leave->status === 'pending')
                                <form action="{{ route('leaves.destroy', $leave) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan pengajuan cuti ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pengajuan cuti</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($leaves->hasPages())<div class="card-footer">{{ $leaves->links() }}</div>@endif
</div>
@endsection
