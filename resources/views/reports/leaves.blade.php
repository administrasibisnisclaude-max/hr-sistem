@extends('layouts.app')
@section('title', 'Laporan Cuti')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-calendar-times me-2 text-danger"></i>Laporan Cuti</h1>
</div>
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <select name="year" class="form-select">
                    @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending">Menunggu</option>
                    <option value="approved">Disetujui</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="leave_type" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="tahunan">Tahunan</option>
                    <option value="sakit">Sakit</option>
                    <option value="darurat">Darurat</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header">{{ $leaves->count() }} pengajuan cuti ditemukan</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Karyawan</th><th>Jenis</th><th>Periode</th><th>Hari</th><th>Alasan</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($leaves as $leave)
                    <tr>
                        <td><div class="fw-semibold">{{ $leave->employee->name }}</div><small class="text-muted">{{ $leave->employee->department?->name }}</small></td>
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
@endsection
