@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<p class="text-muted">Selamat datang, <strong>{{ $employee->name }}</strong>! &mdash; {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>

<div class="row g-3 mb-4">
    <!-- Clock In/Out Card -->
    <div class="col-md-6 col-xl-4">
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-clock me-2"></i>Absensi Hari Ini</div>
            <div class="card-body text-center py-4">
                @if($todayAttendance)
                    <div class="row g-2 my-2">
                        <div class="col-6">
                            <div class="p-2 bg-success bg-opacity-10 rounded">
                                <div class="fw-bold text-success">{{ $todayAttendance->clock_in ?? '-' }}</div>
                                <small class="text-muted">Jam Masuk</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-danger bg-opacity-10 rounded">
                                <div class="fw-bold text-danger">{{ $todayAttendance->clock_out ?? '-' }}</div>
                                <small class="text-muted">Jam Pulang</small>
                            </div>
                        </div>
                    </div>
                    @if(!$todayAttendance->clock_in)
                        <form action="{{ route('attendance.clock') }}" method="POST" class="mt-3">
                            @csrf
                            <button class="btn btn-success w-100"><i class="fas fa-sign-in-alt me-2"></i>Absen Masuk</button>
                        </form>
                    @elseif(!$todayAttendance->clock_out)
                        <form action="{{ route('attendance.clock') }}" method="POST" class="mt-3">
                            @csrf
                            <button class="btn btn-warning w-100"><i class="fas fa-sign-out-alt me-2"></i>Absen Pulang</button>
                        </form>
                    @else
                        <div class="badge bg-success p-2 w-100 mt-3">
                            <i class="fas fa-check me-1"></i>Absensi Selesai
                        </div>
                    @endif
                @else
                    <form action="{{ route('attendance.clock') }}" method="POST">
                        @csrf
                        <button class="btn btn-primary w-100"><i class="fas fa-sign-in-alt me-2"></i>Absen Masuk</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Leave Balance -->
    <div class="col-md-6 col-xl-4">
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-calendar-times me-2"></i>Saldo Cuti {{ date('Y') }}</div>
            <div class="card-body text-center py-4">
                @if($leaveBalance)
                    <div class="mt-1">
                        <div class="display-4 fw-bold text-primary">{{ $leaveBalance->remaining_days }}</div>
                        <div class="text-muted">Hari Tersisa</div>
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: {{ $leaveBalance->total_days > 0 ? ($leaveBalance->remaining_days / $leaveBalance->total_days * 100) : 0 }}%"></div>
                        </div>
                        <small class="text-muted">{{ $leaveBalance->used_days }} / {{ $leaveBalance->total_days }} hari digunakan</small>
                    </div>
                @else
                    <p class="text-muted">Belum ada data saldo cuti</p>
                @endif
                <a href="{{ route('leaves.create') }}" class="btn btn-outline-warning mt-3 btn-sm">
                    <i class="fas fa-plus me-1"></i>Ajukan Cuti
                </a>
            </div>
        </div>
    </div>

    <!-- Latest Payroll -->
    <div class="col-md-6 col-xl-4">
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-money-bill-wave me-2"></i>Gaji Terakhir</div>
            <div class="card-body text-center py-4">
                @if($latestPayroll)
                    <div class="mt-1">
                        <div class="fs-3 fw-bold text-success">Rp {{ number_format($latestPayroll->net_salary, 0, ',', '.') }}</div>
                        <div class="text-muted">{{ $latestPayroll->period_label }}</div>
                    </div>
                    <a href="{{ route('payroll.slip', $latestPayroll) }}" class="btn btn-outline-success mt-3 btn-sm">
                        <i class="fas fa-file-pdf me-1"></i>Lihat Slip
                    </a>
                @else
                    <p class="text-muted mt-3">Belum ada data penggajian</p>
                @endif
            </div>
        </div>
    </div>
</div>

@if($pendingLeaves > 0)
    <div class="alert alert-warning">
        <i class="fas fa-clock me-2"></i>
        Anda memiliki <strong>{{ $pendingLeaves }}</strong> pengajuan cuti yang sedang menunggu persetujuan.
        <a href="{{ route('leaves.own') }}" class="alert-link ms-1">Lihat &rarr;</a>
    </div>
@endif

<!-- Announcements -->
<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-bullhorn me-2"></i>Pengumuman Terbaru</span>
        <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-outline-warning">Semua</a>
    </div>
    <div class="card-body p-0">
        @forelse($announcements as $announcement)
            <div class="p-3 border-bottom">
                <div class="d-flex align-items-start">
                    <div class="me-3 mt-1">
                        <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                            <i class="fas fa-bullhorn text-warning small"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $announcement->title }}</div>
                        <div class="text-muted small mt-1">{{ strip_tags($announcement->content) }}</div>
                        <div class="text-muted mt-1" style="font-size:0.75rem;">{{ $announcement->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-4 text-center text-muted">
                <i class="fas fa-bullhorn fa-2x mb-2 opacity-25"></i>
                <p class="small mb-0">Belum ada pengumuman</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
