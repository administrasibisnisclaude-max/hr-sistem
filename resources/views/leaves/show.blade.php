@extends('layouts.app')
@section('title', 'Detail Cuti')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('leaves.index') }}">Cuti</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>
<div class="card mb-4" style="max-width:700px;">
    <div class="card-header"><i class="fas fa-calendar-times me-2"></i>Detail Pengajuan Cuti</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><small class="text-muted d-block">Karyawan</small><strong>{{ $leave->employee->name }}</strong><br><small class="text-muted">{{ $leave->employee->department?->name }}</small></div>
            <div class="col-md-6"><small class="text-muted d-block">Jenis Cuti</small><span class="badge bg-secondary">{{ $leave->leave_type_label }}</span></div>
            <div class="col-md-6"><small class="text-muted d-block">Tanggal Mulai</small><strong>{{ $leave->start_date->translatedFormat('d F Y') }}</strong></div>
            <div class="col-md-6"><small class="text-muted d-block">Tanggal Selesai</small><strong>{{ $leave->end_date->translatedFormat('d F Y') }}</strong></div>
            <div class="col-md-6"><small class="text-muted d-block">Jumlah Hari</small><strong>{{ $leave->days }} hari</strong></div>
            <div class="col-md-6"><small class="text-muted d-block">Status</small>{!! $leave->status_badge !!}</div>
            <div class="col-12"><small class="text-muted d-block">Alasan</small><p class="mb-0">{{ $leave->reason }}</p></div>
            @if($leave->notes)
                <div class="col-12"><small class="text-muted d-block">Catatan Persetujuan</small><div class="p-3 bg-light rounded">{{ $leave->notes }}</div></div>
            @endif
            @if($leave->approver)
                <div class="col-md-6"><small class="text-muted d-block">Disetujui/Ditolak Oleh</small><strong>{{ $leave->approver->name }}</strong></div>
                <div class="col-md-6"><small class="text-muted d-block">Tanggal Persetujuan</small><strong>{{ $leave->approved_at?->format('d/m/Y H:i') }}</strong></div>
            @endif
        </div>

        @if($leave->status === 'pending')
            @can('approve leaves')
                <hr>
                <div class="row g-2">
                    <div class="col-md-6">
                        <form action="{{ route('leaves.approve', $leave) }}" method="POST">
                            @csrf
                            <div class="mb-2"><textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="Catatan (opsional)"></textarea></div>
                            <button type="submit" class="btn btn-success w-100"><i class="fas fa-check me-2"></i>Setujui</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('leaves.reject', $leave) }}" method="POST">
                            @csrf
                            <div class="mb-2"><textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="Alasan penolakan *" required></textarea></div>
                            <button type="submit" class="btn btn-danger w-100"><i class="fas fa-times me-2"></i>Tolak</button>
                        </form>
                    </div>
                </div>
            @endcan
        @endif
    </div>
</div>
@endsection
