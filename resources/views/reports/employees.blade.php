@extends('layouts.app')

@section('title', 'Laporan Karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Laporan</a></li>
    <li class="breadcrumb-item active">Karyawan</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>Laporan Karyawan</h4>
    <div class="d-flex gap-2">
        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf me-1"></i>PDF</a>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success btn-sm"><i class="fas fa-file-excel me-1"></i>Excel</a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header"><i class="fas fa-filter me-2"></i>Filter</div>
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <select name="department_id" class="form-select">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach(['tetap' => 'Tetap', 'kontrak' => 'Kontrak', 'magang' => 'Magang', 'tidak_aktif' => 'Tidak Aktif'] as $val => $label)
                        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">{{ $employees->count() }} karyawan ditemukan</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Departemen</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th>Tanggal Masuk</th>
                        <th>Telepon</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                        @php $statusColors = ['tetap' => 'success', 'kontrak' => 'warning', 'magang' => 'info', 'tidak_aktif' => 'secondary']; @endphp
                        <tr>
                            <td class="fw-semibold text-primary">{{ $emp->nik }}</td>
                            <td>{{ $emp->name }}</td>
                            <td>{{ $emp->department?->name ?? '-' }}</td>
                            <td>{{ $emp->position?->name ?? '-' }}</td>
                            <td><span class="badge bg-{{ $statusColors[$emp->employment_status] ?? 'secondary' }}">{{ $emp->status_label }}</span></td>
                            <td>{{ $emp->hire_date?->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ $emp->phone ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data</td></tr>
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
