@extends('layouts.app')

@section('title', 'Laporan Penggajian')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Laporan</a></li>
    <li class="breadcrumb-item active">Penggajian</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0"><i class="fas fa-money-bill-wave me-2 text-warning"></i>Laporan Penggajian</h4>
    <div class="d-flex gap-2">
        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf me-1"></i>PDF</a>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success btn-sm"><i class="fas fa-file-excel me-1"></i>Excel</a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header"><i class="fas fa-filter me-2"></i>Filter Periode</div>
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Bulan</label>
                <select name="month" class="form-select">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Tahun</label>
                <select name="year" class="form-select">
                    @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Departemen</label>
                <select name="department_id" class="form-select">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block w-100"><i class="fas fa-search me-1"></i>Tampilkan</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="card text-center p-3">
            <div class="text-muted small">Total Gaji Kotor</div>
            <div class="fs-4 fw-bold">Rp {{ number_format($totalGross, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-center p-3">
            <div class="text-muted small">Total Gaji Bersih</div>
            <div class="fs-4 fw-bold text-success">Rp {{ number_format($totalNet, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Departemen</th>
                        <th>Gaji Pokok</th>
                        <th>Tunjangan</th>
                        <th>Potongan</th>
                        <th>Bonus</th>
                        <th class="fw-bold">Gaji Bersih</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $p)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $p->employee->name }}</div>
                                <small class="text-muted">{{ $p->employee->nik }}</small>
                            </td>
                            <td>{{ $p->employee->department?->name ?? '-' }}</td>
                            <td>{{ number_format($p->basic_salary, 0, ',', '.') }}</td>
                            <td class="text-success">+{{ number_format($p->total_allowance, 0, ',', '.') }}</td>
                            <td class="text-danger">-{{ number_format($p->total_deduction, 0, ',', '.') }}</td>
                            <td class="text-info">{{ number_format($p->bonus, 0, ',', '.') }}</td>
                            <td class="fw-bold">Rp {{ number_format($p->net_salary, 0, ',', '.') }}</td>
                            <td>
                                @php $statusColors = ['draft' => 'secondary', 'approved' => 'primary', 'paid' => 'success']; @endphp
                                <span class="badge bg-{{ $statusColors[$p->status] ?? 'secondary' }}">{{ ucfirst($p->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data penggajian</td></tr>
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
