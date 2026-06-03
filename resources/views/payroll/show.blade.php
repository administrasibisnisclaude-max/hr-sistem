@extends('layouts.app')
@section('title', 'Detail Penggajian')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-money-bill-wave me-2 text-primary"></i>Detail Penggajian</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('payroll.slip', $payroll) }}" class="btn btn-outline-secondary"><i class="fas fa-file-alt me-2"></i>Slip Gaji</a>
        <a href="{{ route('payroll.download', $payroll) }}" class="btn btn-outline-success"><i class="fas fa-download me-2"></i>Unduh PDF</a>
        <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header">Informasi Karyawan & Periode</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Karyawan</small>
                        <strong>{{ $payroll->employee->name }}</strong><br>
                        <small class="text-muted">{{ $payroll->employee->nik }} - {{ $payroll->employee->position?->name }}</small>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Departemen</small>
                        <strong>{{ $payroll->employee->department?->name }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Periode</small>
                        <strong>{{ $payroll->period_label }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Status</small>
                        @php $statusColors = ['draft' => 'secondary', 'approved' => 'primary', 'paid' => 'success']; @endphp
                        <span class="badge bg-{{ $statusColors[$payroll->status] ?? 'secondary' }}">{{ ucfirst($payroll->status) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payroll Components -->
        <div class="card mb-3">
            <div class="card-header">Komponen Gaji</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Komponen</th><th>Jenis</th><th class="text-end">Jumlah</th></tr></thead>
                    <tbody>
                        <tr>
                            <td>Gaji Pokok</td>
                            <td><span class="badge bg-primary">Gaji Pokok</span></td>
                            <td class="text-end fw-semibold">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                        </tr>
                        @if($payroll->overtime_pay > 0)
                            <tr>
                                <td>Uang Lembur</td>
                                <td><span class="badge bg-info">Lembur</span></td>
                                <td class="text-end text-success">Rp {{ number_format($payroll->overtime_pay, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        @foreach($payroll->items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>
                                    @php $typeColors = ['allowance' => 'success', 'deduction' => 'danger', 'bonus' => 'warning']; $typeLabels = ['allowance' => 'Tunjangan', 'deduction' => 'Potongan', 'bonus' => 'Bonus']; @endphp
                                    <span class="badge bg-{{ $typeColors[$item->type] ?? 'secondary' }}">{{ $typeLabels[$item->type] ?? $item->type }}</span>
                                </td>
                                <td class="text-end {{ $item->type === 'deduction' ? 'text-danger' : 'text-success' }}">
                                    {{ $item->type === 'deduction' ? '-' : '+' }}Rp {{ number_format($item->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="2" class="fw-bold">Gaji Kotor</td>
                            <td class="text-end fw-bold">Rp {{ number_format($payroll->gross_salary, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="table-primary">
                            <td colspan="2" class="fw-bold fs-5">Gaji Bersih</td>
                            <td class="text-end fw-bold fs-5 text-primary">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">Aksi</div>
            <div class="card-body d-flex flex-column gap-2">
                @can('manage payroll')
                    @if($payroll->status === 'draft')
                        <form action="{{ route('payroll.approve', $payroll) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-check me-2"></i>Setujui Penggajian</button>
                        </form>
                    @elseif($payroll->status === 'approved')
                        <form action="{{ route('payroll.paid', $payroll) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100"><i class="fas fa-money-bill me-2"></i>Tandai Sudah Dibayar</button>
                        </form>
                    @endif
                @endcan
                <a href="{{ route('payroll.slip', $payroll) }}" class="btn btn-outline-secondary"><i class="fas fa-file-alt me-2"></i>Lihat Slip Gaji</a>
                <a href="{{ route('payroll.download', $payroll) }}" class="btn btn-outline-danger"><i class="fas fa-file-pdf me-2"></i>Download PDF</a>
            </div>
        </div>

        @if($payroll->notes)
            <div class="card">
                <div class="card-header">Catatan</div>
                <div class="card-body">{{ $payroll->notes }}</div>
            </div>
        @endif
    </div>
</div>
@endsection
