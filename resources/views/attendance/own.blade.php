@extends('layouts.app')
@section('title', 'Absensi Saya')
@section('breadcrumb')
    <li class="breadcrumb-item active">Absensi Saya</li>
@endsection
@section('content')
<div class="card mb-4">
    <div class="card-header"><i class="fas fa-clock me-2"></i>Absensi Hari Ini &mdash; {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                @if($todayAttendance)
                    <div class="d-flex gap-3 align-items-center">
                        <div class="text-center">
                            <small class="text-muted d-block">Jam Masuk</small>
                            <strong class="text-success fs-5">{{ $todayAttendance->clock_in ?? '-' }}</strong>
                        </div>
                        <div class="text-center">
                            <small class="text-muted d-block">Jam Pulang</small>
                            <strong class="text-danger fs-5">{{ $todayAttendance->clock_out ?? '-' }}</strong>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                @if($todayAttendance)
                    @if(!$todayAttendance->clock_in)
                        <form action="{{ route('attendance.clock') }}" method="POST">
                            @csrf
                            <button class="btn btn-success"><i class="fas fa-sign-in-alt me-2"></i>Absen Masuk</button>
                        </form>
                    @elseif(!$todayAttendance->clock_out)
                        <form action="{{ route('attendance.clock') }}" method="POST">
                            @csrf
                            <button class="btn btn-warning"><i class="fas fa-sign-out-alt me-2"></i>Absen Pulang</button>
                        </form>
                    @else
                        <span class="badge bg-success p-2"><i class="fas fa-check me-1"></i>Absensi Selesai</span>
                    @endif
                @else
                    <form action="{{ route('attendance.clock') }}" method="POST">
                        @csrf
                        <button class="btn btn-primary"><i class="fas fa-sign-in-alt me-2"></i>Absen Masuk Sekarang</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
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
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Tampilkan</button>
            </div>
        </form>
    </div>
</div>

@php
    $hadir = $attendances->where('status', 'hadir')->count();
    $izin = $attendances->where('status', 'izin')->count();
    $sakit = $attendances->where('status', 'sakit')->count();
    $alpha = $attendances->where('status', 'alpha')->count();
    $cuti = $attendances->where('status', 'cuti')->count();
@endphp
<div class="row g-3 mb-4">
    <div class="col"><div class="card text-center p-3"><div class="fs-4 fw-bold text-success">{{ $hadir }}</div><small class="text-muted">Hadir</small></div></div>
    <div class="col"><div class="card text-center p-3"><div class="fs-4 fw-bold text-warning">{{ $izin }}</div><small class="text-muted">Izin</small></div></div>
    <div class="col"><div class="card text-center p-3"><div class="fs-4 fw-bold text-info">{{ $sakit }}</div><small class="text-muted">Sakit</small></div></div>
    <div class="col"><div class="card text-center p-3"><div class="fs-4 fw-bold text-danger">{{ $alpha }}</div><small class="text-muted">Alpha</small></div></div>
    <div class="col"><div class="card text-center p-3"><div class="fs-4 fw-bold text-primary">{{ $cuti }}</div><small class="text-muted">Cuti</small></div></div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="datatablesSimple">
                <thead><tr><th>Tanggal</th><th>Hari</th><th>Jam Masuk</th><th>Jam Pulang</th><th>Lembur</th><th>Status</th><th>Catatan</th></tr></thead>
                <tbody>
                    @forelse($attendances as $att)
                        @php $colors = ['hadir' => 'success', 'izin' => 'warning', 'sakit' => 'info', 'alpha' => 'danger', 'cuti' => 'primary']; @endphp
                        <tr>
                            <td>{{ $att->date->format('d/m/Y') }}</td>
                            <td>{{ $att->date->translatedFormat('l') }}</td>
                            <td>{{ $att->clock_in ?? '-' }}</td>
                            <td>{{ $att->clock_out ?? '-' }}</td>
                            <td>{{ $att->overtime_hours > 0 ? $att->overtime_hours . 'j' : '-' }}</td>
                            <td><span class="badge bg-{{ $colors[$att->status] ?? 'secondary' }}">{{ ucfirst($att->status) }}</span></td>
                            <td class="small text-muted">{{ $att->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data absensi bulan ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>$(document).ready(function() { if ($.fn.DataTable) $('#datatablesSimple').DataTable(); });</script>
@endsection
