@extends('layouts.app')
@section('title', 'Pengajuan Cuti')
@section('breadcrumb')
    <li class="breadcrumb-item active">Cuti</li>
@endsection
@section('content')
<div class="d-flex justify-content-end mb-3">
    @can('manage leaves')
        <div class="d-flex gap-2">
            <a href="{{ route('leaves.balance') }}" class="btn btn-outline-info"><i class="fas fa-balance-scale me-2"></i>Saldo Cuti</a>
            <a href="{{ route('leaves.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Tambah</a>
        </div>
    @endcan
</div>

<div class="card mb-4">
    <div class="card-header"><i class="fas fa-filter me-2"></i>Filter</div>
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="employee_id" class="form-select">
                    <option value="">Semua Karyawan</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Filter</button>
                <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary ms-1">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="datatablesSimple">
                <thead><tr><th>Karyawan</th><th>Jenis</th><th>Tanggal</th><th>Hari</th><th>Alasan</th><th>Status</th><th>Disetujui oleh</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr>
                            <td><div class="fw-semibold">{{ $leave->employee->name }}</div><small class="text-muted">{{ $leave->employee->department?->name }}</small></td>
                            <td><span class="badge bg-secondary">{{ $leave->leave_type_label }}</span></td>
                            <td class="small">{{ $leave->start_date->format('d/m/Y') }} - {{ $leave->end_date->format('d/m/Y') }}</td>
                            <td class="text-center">{{ $leave->days }}</td>
                            <td class="small text-muted">{{ Str::limit($leave->reason, 40) }}</td>
                            <td>{!! $leave->status_badge !!}</td>
                            <td class="small">{{ $leave->approver?->name ?? '-' }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('leaves.show', $leave) }}" class="btn btn-outline-info"><i class="fas fa-eye"></i></a>
                                    @if($leave->status === 'pending')
                                        @can('approve leaves')
                                            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $leave->id }}"><i class="fas fa-check"></i></button>
                                            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $leave->id }}"><i class="fas fa-times"></i></button>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>

                        @if($leave->status === 'pending')
                            <div class="modal fade" id="approveModal{{ $leave->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('leaves.approve', $leave) }}" method="POST">
                                            @csrf
                                            <div class="modal-header"><h5 class="modal-title">Setujui Cuti</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                            <div class="modal-body">
                                                <p>Setujui cuti <strong>{{ $leave->employee->name }}</strong> tanggal <strong>{{ $leave->start_date->format('d/m/Y') }}</strong> - <strong>{{ $leave->end_date->format('d/m/Y') }}</strong> ({{ $leave->days }} hari)?</p>
                                                <div class="mb-3"><label class="form-label">Catatan (opsional)</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i>Setujui</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('leaves.reject', $leave) }}" method="POST">
                                            @csrf
                                            <div class="modal-header"><h5 class="modal-title">Tolak Cuti</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                            <div class="modal-body">
                                                <div class="mb-3"><label class="form-label">Alasan Penolakan *</label><textarea name="notes" class="form-control" rows="3" required></textarea></div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger"><i class="fas fa-times me-1"></i>Tolak</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data pengajuan cuti</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($leaves->hasPages())<div class="mt-3">{{ $leaves->links() }}</div>@endif
    </div>
</div>
@endsection
@section('scripts')
<script>$(document).ready(function() { if ($.fn.DataTable) $('#datatablesSimple').DataTable(); });</script>
@endsection
