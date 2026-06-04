@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="display-6 fw-bold">{{ $totalEmployees }}</div>
                    <div>Karyawan Aktif</div>
                </div>
                <i class="fas fa-users fa-3x opacity-50"></i>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="{{ route('employees.index') }}">Lihat Detail</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white mb-4">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="display-6 fw-bold">{{ $presentToday }}</div>
                    <div>Hadir Hari Ini</div>
                </div>
                <i class="fas fa-user-check fa-3x opacity-50"></i>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="{{ route('attendance.index') }}">Lihat Detail</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white mb-4">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="display-6 fw-bold">{{ $onLeave }}</div>
                    <div>Sedang Cuti</div>
                </div>
                <i class="fas fa-umbrella-beach fa-3x opacity-50"></i>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="{{ route('leaves.index') }}">Lihat Detail</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-danger text-white mb-4">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="fs-5 fw-bold">Rp {{ number_format($totalPayrollThisMonth, 0, ',', '.') }}</div>
                    <div>Total Penggajian</div>
                </div>
                <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="{{ route('payroll.index') }}">Lihat Detail</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Attendance Summary -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="fas fa-clock me-2"></i>Absensi Hari Ini</span>
                <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <div class="p-3 bg-success bg-opacity-10 rounded-3 text-center">
                            <div class="fs-2 fw-bold text-success">{{ $attendanceSummary['hadir'] }}</div>
                            <div class="small text-muted">Hadir</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-warning bg-opacity-10 rounded-3 text-center">
                            <div class="fs-2 fw-bold text-warning">{{ $attendanceSummary['izin'] }}</div>
                            <div class="small text-muted">Izin</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-info bg-opacity-10 rounded-3 text-center">
                            <div class="fs-2 fw-bold text-info">{{ $attendanceSummary['sakit'] }}</div>
                            <div class="small text-muted">Sakit</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-danger bg-opacity-10 rounded-3 text-center">
                            <div class="fs-2 fw-bold text-danger">{{ $attendanceSummary['alpha'] }}</div>
                            <div class="small text-muted">Alpha</div>
                        </div>
                    </div>
                </div>
                @if($pendingLeaveRequests > 0)
                    <div class="alert alert-warning mt-3 mb-0 py-2 small">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>{{ $pendingLeaveRequests }}</strong> pengajuan cuti menunggu persetujuan
                        <a href="{{ route('leaves.index') }}?status=pending" class="ms-1">Proses &rarr;</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Announcements -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="fas fa-bullhorn me-2"></i>Pengumuman</span>
                <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-outline-warning">Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($announcements as $announcement)
                    <div class="p-3 border-bottom">
                        <div class="fw-semibold small text-dark">{{ $announcement->title }}</div>
                        <div class="text-muted" style="font-size:0.78rem; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                            {{ strip_tags($announcement->content) }}
                        </div>
                        <div class="text-muted mt-1" style="font-size:0.72rem;">
                            <i class="fas fa-clock me-1"></i>{{ $announcement->created_at->diffForHumans() }}
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
    </div>

    <!-- Expiring Contracts -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="fas fa-file-contract me-2"></i>Kontrak Hampir Habis</span>
                <a href="{{ route('contracts.index') }}?expiring=1" class="btn btn-sm btn-outline-danger">Lihat</a>
            </div>
            <div class="card-body p-0">
                @forelse($expiringContracts as $contract)
                    <div class="p-3 border-bottom d-flex align-items-center">
                        <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center me-3" style="width:36px;height:36px;font-size:0.8rem;font-weight:700;">
                            {{ $contract->days_until_expiry }}
                        </div>
                        <div>
                            <div class="fw-semibold small">{{ $contract->employee->name }}</div>
                            <div class="text-muted" style="font-size:0.75rem;">
                                Berakhir: {{ $contract->end_date->format('d/m/Y') }}
                                <span class="badge bg-danger ms-1" style="font-size:0.65rem;">{{ $contract->days_until_expiry }} hari lagi</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-check-circle fa-2x mb-2 text-success opacity-50"></i>
                        <p class="small mb-0">Tidak ada kontrak yang akan habis</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Recent Employees -->
<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-user-plus me-2"></i>Karyawan Terbaru</span>
        <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-primary">Semua Karyawan</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Departemen</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th>Bergabung</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentEmployees as $emp)
                        <tr>
                            <td class="fw-semibold text-primary">{{ $emp->nik }}</td>
                            <td>
                                <a href="{{ route('employees.show', $emp) }}" class="text-dark text-decoration-none fw-semibold">{{ $emp->name }}</a>
                            </td>
                            <td>{{ $emp->department?->name ?? '-' }}</td>
                            <td>{{ $emp->position?->name ?? '-' }}</td>
                            <td>
                                @php $statusColors = ['tetap' => 'success', 'kontrak' => 'warning', 'magang' => 'info', 'tidak_aktif' => 'secondary']; @endphp
                                <span class="badge bg-{{ $statusColors[$emp->employment_status] ?? 'secondary' }}">{{ $emp->status_label }}</span>
                            </td>
                            <td>{{ $emp->hire_date?->format('d/m/Y') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data karyawan</td></tr>
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
