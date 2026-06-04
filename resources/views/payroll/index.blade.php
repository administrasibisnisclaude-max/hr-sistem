@extends('layouts.app')
@section('title', 'Penggajian')
@section('breadcrumb')
    <li class="breadcrumb-item active">Penggajian</li>
@endsection
@section('content')
<div class="d-flex justify-content-end mb-3">
    @can('manage payroll')
        <a href="{{ route('payroll.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Buat Penggajian</a>
    @endcan
</div>

<div class="card mb-4">
    <div class="card-header"><i class="fas fa-filter me-2"></i>Filter</div>
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <select name="month" class="form-select">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month', $currentMonth) == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <select name="year" class="form-select">
                    @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                        <option value="{{ $y }}" {{ request('year', $currentYear) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Filter</button>
                <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary ms-1">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="datatablesSimple">
                <thead><tr><th>Karyawan</th><th>Departemen</th><th>Periode</th><th>Gaji Pokok</th><th>Tunjangan</th><th>Potongan</th><th>Gaji Bersih</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                        <tr>
                            <td><div class="fw-semibold">{{ $payroll->employee->name }}</div><small class="text-muted">{{ $payroll->employee->nik }}</small></td>
                            <td>{{ $payroll->employee->department?->name ?? '-' }}</td>
                            <td>{{ $payroll->period_label }}</td>
                            <td>{{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                            <td class="text-success">+{{ number_format($payroll->total_allowance + $payroll->bonus, 0, ',', '.') }}</td>
                            <td class="text-danger">-{{ number_format($payroll->total_deduction, 0, ',', '.') }}</td>
                            <td class="fw-bold">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                            <td>
                                @php $statusColors = ['draft' => 'secondary', 'approved' => 'primary', 'paid' => 'success']; $statusLabels = ['draft' => 'Draft', 'approved' => 'Disetujui', 'paid' => 'Dibayar']; @endphp
                                <span class="badge bg-{{ $statusColors[$payroll->status] ?? 'secondary' }}">{{ $statusLabels[$payroll->status] ?? $payroll->status }}</span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('payroll.show', $payroll) }}" class="btn btn-outline-info"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('payroll.slip', $payroll) }}" class="btn btn-outline-secondary" title="Slip Gaji"><i class="fas fa-file-alt"></i></a>
                                    @can('manage payroll')
                                        @if($payroll->status === 'draft')
                                            <form action="{{ route('payroll.approve', $payroll) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-primary" title="Setujui"><i class="fas fa-check"></i></button>
                                            </form>
                                        @elseif($payroll->status === 'approved')
                                            <form action="{{ route('payroll.paid', $payroll) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success" title="Tandai Dibayar"><i class="fas fa-money-bill"></i></button>
                                            </form>
                                        @endif
                                        @if($payroll->status === 'draft')
                                            <form action="{{ route('payroll.destroy', $payroll) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data penggajian ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">Belum ada data penggajian</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payrolls->hasPages())<div class="mt-3">{{ $payrolls->links() }}</div>@endif
    </div>
</div>
@endsection
@section('scripts')
<script>$(document).ready(function() { if ($.fn.DataTable) $('#datatablesSimple').DataTable(); });</script>
@endsection
