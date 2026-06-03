@extends('layouts.app')
@section('title', 'Absensi')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-clock me-2 text-primary"></i>Data Absensi</h1>
    <div class="d-flex gap-2">
        @can('manage attendance')
            <a href="{{ route('attendance.recap') }}" class="btn btn-outline-info"><i class="fas fa-calendar-check me-2"></i>Rekap</a>
            <a href="{{ route('attendance.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Tambah</a>
        @endcan
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ $date->format('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <select name="department_id" class="form-select">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Cari</button>
                <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary ms-1">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Absensi tanggal: <strong>{{ $date->translatedFormat('l, d F Y') }}</strong></div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Karyawan</th><th>Departemen</th><th>Jam Masuk</th><th>Jam Pulang</th><th>Lembur</th><th>Status</th><th>Catatan</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
                @forelse($attendances as $att)
                    @php $colors = ['hadir' => 'success', 'izin' => 'warning', 'sakit' => 'info', 'alpha' => 'danger', 'cuti' => 'primary']; @endphp
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $att->employee->name }}</div>
                            <small class="text-muted">{{ $att->employee->nik }}</small>
                        </td>
                        <td>{{ $att->employee->department?->name ?? '-' }}</td>
                        <td>{{ $att->clock_in ?? '-' }}</td>
                        <td>{{ $att->clock_out ?? '-' }}</td>
                        <td>{{ $att->overtime_hours > 0 ? $att->overtime_hours . ' jam' : '-' }}</td>
                        <td><span class="badge bg-{{ $colors[$att->status] ?? 'secondary' }}">{{ ucfirst($att->status) }}</span></td>
                        <td class="small text-muted">{{ $att->notes ?? '-' }}</td>
                        <td class="text-end">
                            @can('manage attendance')
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('attendance.edit', $att) }}" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('attendance.destroy', $att) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data absensi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data absensi untuk tanggal ini</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($attendances->hasPages())<div class="card-footer">{{ $attendances->links() }}</div>@endif
</div>
@endsection
