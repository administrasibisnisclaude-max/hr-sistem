@extends('layouts.app')
@section('title', 'Laporan Penggajian')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-money-bill-wave me-2 text-warning"></i>Laporan Penggajian</h1>
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

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="card p-3 text-center">
            <div class="text-muted small">Total Gaji Kotor</div>
            <div class="fs-4 fw-bold">Rp {{ number_format($totalGross, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3 text-center">
            <div class="text-muted small">Total Gaji Bersih</div>
            <div class="fs-4 fw-bold text-success">Rp {{ number_format($totalNet, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Karyawan</th><th>Departemen</th><th>Gaji Pokok</th><th>Tunjangan</th><th>Potongan</th><th>Bonus</th><th class="fw-bold">Gaji Bersih</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($payrolls as $p)
                    <tr>
                        <td><div class="fw-semibold">{{ $p->employee->name }}</div><small class="text-muted">{{ $p->employee->nik }}</small></td>
                        <td>{{ $p->employee->department?->name ?? '-' }}</td>
                        <td>{{ number_format($p->basic_salary, 0, ',', '.') }}</td>
                        <td class="text-success">{{ number_format($p->total_allowance, 0, ',', '.') }}</td>
                        <td class="text-danger">{{ number_format($p->total_deduction, 0, ',', '.') }}</td>
                        <td class="text-info">{{ number_format($p->bonus, 0, ',', '.') }}</td>
                        <td class="fw-bold">Rp {{ number_format($p->net_salary, 0, ',', '.') }}</td>
                        <td><span class="badge bg-{{ ['draft' => 'secondary', 'approved' => 'primary', 'paid' => 'success'][$p->status] ?? 'secondary' }}">{{ ucfirst($p->status) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data penggajian</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
