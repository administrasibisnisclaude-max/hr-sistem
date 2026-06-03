@extends('layouts.app')
@section('title', 'Laporan HR')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-chart-line me-2 text-primary"></i>Laporan HR</h1>
</div>
<div class="row g-3">
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('reports.employees') }}" class="card text-decoration-none h-100 hover-card">
            <div class="card-body text-center py-4">
                <i class="fas fa-users fa-3x text-primary mb-3"></i>
                <h5 class="fw-bold text-dark">Laporan Karyawan</h5>
                <p class="text-muted small mb-0">Data karyawan berdasarkan departemen, jabatan, status</p>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('reports.attendance') }}" class="card text-decoration-none h-100 hover-card">
            <div class="card-body text-center py-4">
                <i class="fas fa-calendar-check fa-3x text-success mb-3"></i>
                <h5 class="fw-bold text-dark">Laporan Absensi</h5>
                <p class="text-muted small mb-0">Rekap kehadiran karyawan per bulan</p>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('reports.payroll') }}" class="card text-decoration-none h-100 hover-card">
            <div class="card-body text-center py-4">
                <i class="fas fa-money-bill-wave fa-3x text-warning mb-3"></i>
                <h5 class="fw-bold text-dark">Laporan Penggajian</h5>
                <p class="text-muted small mb-0">Rekap gaji karyawan per periode</p>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('reports.leaves') }}" class="card text-decoration-none h-100 hover-card">
            <div class="card-body text-center py-4">
                <i class="fas fa-calendar-times fa-3x text-danger mb-3"></i>
                <h5 class="fw-bold text-dark">Laporan Cuti</h5>
                <p class="text-muted small mb-0">Rekap cuti karyawan per tahun</p>
            </div>
        </a>
    </div>
</div>
@endsection
<style>.hover-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); transition: all 0.2s; }</style>
