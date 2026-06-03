@extends('layouts.app')
@section('title', 'Laporan Absensi')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-calendar-check me-2 text-success"></i>Laporan Absensi</h1>
    <div class="d-flex gap-2">
        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf me-1"></i>PDF</a>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success btn-sm"><i class="fas fa-file-excel me-1"></i>Excel</a>
    </div>
</div>
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
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
<div class="card">
    <div class="card-header">Rekap Absensi: {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Karyawan</th><th>Dept.</th><th class="text-center text-success">Hadir</th><th class="text-center text-warning">Izin</th><th class="text-center text-info">Sakit</th><th class="text-center text-danger">Alpha</th><th class="text-center text-primary">Cuti</th><th class="text-center">Lembur</th></tr></thead>
            <tbody>
                @foreach($employees as $emp)
                    <tr>
                        <td><div class="fw-semibold">{{ $emp->name }}</div><small class="text-muted">{{ $emp->nik }}</small></td>
                        <td>{{ $emp->department?->name }}</td>
                        <td class="text-center">{{ $emp->attendances->where('status', 'hadir')->count() }}</td>
                        <td class="text-center">{{ $emp->attendances->where('status', 'izin')->count() ?: '-' }}</td>
                        <td class="text-center">{{ $emp->attendances->where('status', 'sakit')->count() ?: '-' }}</td>
                        <td class="text-center">{{ $emp->attendances->where('status', 'alpha')->count() ?: '-' }}</td>
                        <td class="text-center">{{ $emp->attendances->where('status', 'cuti')->count() ?: '-' }}</td>
                        <td class="text-center">{{ $emp->attendances->sum('overtime_hours') > 0 ? $emp->attendances->sum('overtime_hours') . 'j' : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
