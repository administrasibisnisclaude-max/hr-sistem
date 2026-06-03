@extends('layouts.app')
@section('title', 'Slip Gaji')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-money-bill-wave me-2 text-primary"></i>Slip Gaji</h1>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
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
    @if($payrolls->hasPages())<div class="card-footer">{{ $payrolls->links() }}</div>@endif
</div>
@endsection
