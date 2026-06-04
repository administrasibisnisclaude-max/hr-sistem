@extends('layouts.app')

@section('title', 'Laporan Cuti')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Laporan</a></li>
    <li class="breadcrumb-item active">Cuti</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0"><i class="fas fa-calendar-times me-2 text-danger"></i>Laporan Cuti</h4>
</div>

<div class="card mb-3">
    <div class="card-header"><i class="fas fa-filter me-2"></i>Filter</div>
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Tahun</label>
                <select name="year" class="form-select">
                    @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Jenis Cuti</label>
                <select name="leave_type" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="tahunan" {{ request('leave_type') === 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                    <option value="sakit" {{ request('leave_type') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="darurat" {{ request('leave_type') === 'darurat' ? 'selected' : '' }}>Darurat</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block w-100"><i class="fas fa-search me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">{{ $leaves->count() }} pengajuan cuti ditemukan</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Jenis</th>
                        <th>Periode</th>
                        <th>Hari</th>
                        <th>Alasan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $leave->employee->name }}</div>
                                <small class="text-muted">{{ $leave->employee->department?->name }}</small>
                            </td>
                            <td><span class="badge bg-secondary">{{ $leave->leave_type_label }}</span></td>
                            <td class="small">{{ $leave->start_date->format('d/m/Y') }} - {{ $leave->end_date->format('d/m/Y') }}</td>
                            <td class="text-center fw-bold">{{ $leave->days }}</td>
                            <td class="small">{{ Str::limit($leave->reason, 50) }}</td>
                            <td>{!! $leave->status_badge !!}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data cuti</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        if ($.fn.DataTable && $('#datatablesSimple').length) {
            $('#datatablesSimple').DataTable({ language: { url: '' } });
        }
    });
</script>
@endsection
