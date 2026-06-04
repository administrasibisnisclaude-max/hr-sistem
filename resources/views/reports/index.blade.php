@extends('layouts.app')

@section('title', 'Laporan HR')

@section('breadcrumb')
    <li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')
<div class="mb-4">
    <h4 class="mb-0"><i class="fas fa-chart-line me-2 text-primary"></i>Laporan HR</h4>
</div>

<div class="row g-3">
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('reports.employees') }}" class="text-decoration-none">
            <div class="card h-100 text-center py-4 hover-lift">
                <div class="card-body">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h5 class="fw-bold text-dark">Laporan Karyawan</h5>
                    <p class="text-muted small mb-0">Data karyawan berdasarkan departemen, jabatan, status</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('reports.attendance') }}" class="text-decoration-none">
            <div class="card h-100 text-center py-4 hover-lift">
                <div class="card-body">
                    <i class="fas fa-calendar-check fa-3x text-success mb-3"></i>
                    <h5 class="fw-bold text-dark">Laporan Absensi</h5>
                    <p class="text-muted small mb-0">Rekap kehadiran karyawan per bulan</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('reports.payroll') }}" class="text-decoration-none">
            <div class="card h-100 text-center py-4 hover-lift">
                <div class="card-body">
                    <i class="fas fa-money-bill-wave fa-3x text-warning mb-3"></i>
                    <h5 class="fw-bold text-dark">Laporan Penggajian</h5>
                    <p class="text-muted small mb-0">Rekap gaji karyawan per periode</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('reports.leaves') }}" class="text-decoration-none">
            <div class="card h-100 text-center py-4 hover-lift">
                <div class="card-body">
                    <i class="fas fa-calendar-times fa-3x text-danger mb-3"></i>
                    <h5 class="fw-bold text-dark">Laporan Cuti</h5>
                    <p class="text-muted small mb-0">Rekap cuti karyawan per tahun</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .hover-lift { transition: transform 0.2s, box-shadow 0.2s; }
    .hover-lift:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important; }
</style>
@endsection
