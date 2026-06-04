@extends('layouts.app')
@section('title', 'Slip Gaji')
@section('breadcrumb')
    <li class="breadcrumb-item active">Slip Gaji</li>
@endsection
@section('content')
<div class="card mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="datatablesSimple">
                <thead><tr><th>Periode</th><th>Gaji Pokok</th><th>Tunjangan</th><th>Potongan</th><th>Gaji Bersih</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                        <tr>
                            <td class="fw-semibold">{{ $payroll->period_label }}</td>
                            <td>Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                            <td class="text-success">+{{ number_format($payroll->total_allowance + $payroll->bonus, 0, ',', '.') }}</td>
                            <td class="text-danger">-{{ number_format($payroll->total_deduction, 0, ',', '.') }}</td>
                            <td class="fw-bold text-success">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                            <td>
                                @php $statusColors = ['draft' => 'secondary', 'approved' => 'primary', 'paid' => 'success']; @endphp
                                <span class="badge bg-{{ $statusColors[$payroll->status] ?? 'secondary' }}">{{ ucfirst($payroll->status) }}</span>
                            </td>
                            <td>
                                @if($payroll->status === 'paid')
                                    <a href="{{ route('payroll.slip.own', $payroll) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye me-1"></i>Lihat</a>
                                    <a href="{{ route('payroll.download.own', $payroll) }}" class="btn btn-sm btn-outline-success ms-1"><i class="fas fa-download me-1"></i>Unduh</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data penggajian</td></tr>
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
