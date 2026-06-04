@extends('layouts.app')
@section('title', 'Rekap Absensi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Absensi</a></li>
    <li class="breadcrumb-item active">Rekap</li>
@endsection
@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('reports.attendance', ['month' => $month, 'year' => $year, 'export' => 'excel']) }}" class="btn btn-success">
        <i class="fas fa-file-excel me-2"></i>Export Excel
    </a>
</div>

<div class="card mb-4">
    <div class="card-header"><i class="fas fa-filter me-2"></i>Filter</div>
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <select name="month" class="form-select">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <select name="year" class="form-select">
                    @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <select name="department_id" class="form-select">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Tampilkan</button>
            </div>
        </form>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">Rekap Absensi: {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="datatablesSimple" style="font-size:0.85rem;">
                <thead>
                    <tr class="text-center">
                        <th rowspan="2" class="align-middle" style="min-width:150px;">Nama</th>
                        <th rowspan="2" class="align-middle">Dept.</th>
                        <th colspan="5">Status Kehadiran</th>
                        <th rowspan="2" class="align-middle">Lembur</th>
                    </tr>
                    <tr class="text-center">
                        <th class="text-success">H</th>
                        <th class="text-warning">I</th>
                        <th class="text-info">S</th>
                        <th class="text-danger">A</th>
                        <th class="text-primary">C</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $emp)
                        @php
                            $hadir = $emp->attendances->where('status', 'hadir')->count();
                            $izin = $emp->attendances->where('status', 'izin')->count();
                            $sakit = $emp->attendances->where('status', 'sakit')->count();
                            $alpha = $emp->attendances->where('status', 'alpha')->count();
                            $cuti = $emp->attendances->where('status', 'cuti')->count();
                            $overtime = $emp->attendances->sum('overtime_hours');
                        @endphp
                        <tr>
                            <td><div class="fw-semibold">{{ $emp->name }}</div><small class="text-muted">{{ $emp->nik }}</small></td>
                            <td class="small">{{ $emp->department?->name }}</td>
                            <td class="text-center text-success fw-bold">{{ $hadir }}</td>
                            <td class="text-center text-warning fw-bold">{{ $izin ?: '-' }}</td>
                            <td class="text-center text-info fw-bold">{{ $sakit ?: '-' }}</td>
                            <td class="text-center text-danger fw-bold">{{ $alpha ?: '-' }}</td>
                            <td class="text-center text-primary fw-bold">{{ $cuti ?: '-' }}</td>
                            <td class="text-center">{{ $overtime > 0 ? $overtime . 'j' : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-2 small text-muted">H=Hadir, I=Izin, S=Sakit, A=Alpha, C=Cuti</div>
    </div>
</div>
@endsection
@section('scripts')
<script>$(document).ready(function() { if ($.fn.DataTable) $('#datatablesSimple').DataTable(); });</script>
@endsection
