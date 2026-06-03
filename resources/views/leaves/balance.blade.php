@extends('layouts.app')
@section('title', 'Saldo Cuti')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-balance-scale me-2 text-primary"></i>Saldo Cuti Karyawan</h1>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
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

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Karyawan</th><th>Departemen</th><th>Total Jatah</th><th>Digunakan</th><th>Sisa</th><th>Progress</th></tr></thead>
            <tbody>
                @forelse($balances as $balance)
                    <tr>
                        <td><div class="fw-semibold">{{ $balance->employee->name }}</div><small class="text-muted">{{ $balance->employee->nik }}</small></td>
                        <td>{{ $balance->employee->department?->name ?? '-' }}</td>
                        <td class="text-center fw-bold">{{ $balance->total_days }}</td>
                        <td class="text-center text-warning fw-bold">{{ $balance->used_days }}</td>
                        <td class="text-center text-success fw-bold">{{ $balance->remaining_days }}</td>
                        <td style="min-width:150px;">
                            <div class="progress" style="height:8px;">
                                <div class="progress-bar {{ $balance->remaining_days <= 3 ? 'bg-danger' : 'bg-success' }}" style="width:{{ $balance->total_days > 0 ? ($balance->remaining_days / $balance->total_days * 100) : 0 }}%"></div>
                            </div>
                            <small class="text-muted">{{ $balance->total_days > 0 ? round($balance->remaining_days / $balance->total_days * 100) : 0 }}% tersisa</small>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data saldo cuti untuk tahun {{ $year }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
