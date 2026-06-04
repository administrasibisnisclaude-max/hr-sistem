@extends('layouts.app')
@section('title', 'Slip Gaji')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('payroll.index') }}">Penggajian</a></li>
    <li class="breadcrumb-item active">Slip Gaji</li>
@endsection
@section('content')
<div class="d-flex justify-content-end mb-3">
    <div class="d-flex gap-2">
        <a href="{{ route('payroll.download', $payroll) }}" class="btn btn-danger"><i class="fas fa-file-pdf me-2"></i>Download PDF</a>
        <button onclick="window.print()" class="btn btn-outline-secondary"><i class="fas fa-print me-2"></i>Cetak</button>
    </div>
</div>

<div class="card mb-4" style="max-width:700px; margin:0 auto;" id="slipContent">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
            <div class="d-flex align-items-center">
                @php $logo = $company->get('company_logo')?->value; @endphp
                @if($logo)
                    <img src="{{ Storage::url($logo) }}" alt="Logo" style="height:50px;margin-right:15px;">
                @else
                    <div style="width:50px;height:50px;background:#0d6efd;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-right:15px;">
                        <i class="fas fa-building text-white fa-lg"></i>
                    </div>
                @endif
                <div>
                    <h5 class="fw-bold mb-0">{{ $company->get('company_name')?->value ?? 'Perusahaan' }}</h5>
                    <small class="text-muted">{{ $company->get('company_address')?->value }}</small>
                </div>
            </div>
            <div class="text-end">
                <h6 class="fw-bold text-primary mb-0">SLIP GAJI</h6>
                <small class="text-muted">{{ $payroll->period_label }}</small>
            </div>
        </div>

        <div class="row g-3 mb-4 p-3 bg-light rounded">
            <div class="col-6"><small class="text-muted d-block">Nama</small><strong>{{ $payroll->employee->name }}</strong></div>
            <div class="col-6"><small class="text-muted d-block">NIK</small><strong>{{ $payroll->employee->nik }}</strong></div>
            <div class="col-6"><small class="text-muted d-block">Jabatan</small><strong>{{ $payroll->employee->position?->name ?? '-' }}</strong></div>
            <div class="col-6"><small class="text-muted d-block">Departemen</small><strong>{{ $payroll->employee->department?->name ?? '-' }}</strong></div>
        </div>

        <table class="table table-sm">
            <tbody>
                <tr class="table-light"><td colspan="2" class="fw-bold">PENGHASILAN</td></tr>
                <tr><td>Gaji Pokok</td><td class="text-end">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td></tr>
                @if($payroll->overtime_pay > 0)
                    <tr><td>Uang Lembur</td><td class="text-end">Rp {{ number_format($payroll->overtime_pay, 0, ',', '.') }}</td></tr>
                @endif
                @foreach($payroll->items->where('type', 'allowance') as $item)
                    <tr><td>{{ $item->name }}</td><td class="text-end text-success">Rp {{ number_format($item->amount, 0, ',', '.') }}</td></tr>
                @endforeach
                @foreach($payroll->items->where('type', 'bonus') as $item)
                    <tr><td>{{ $item->name }} (Bonus)</td><td class="text-end text-success">Rp {{ number_format($item->amount, 0, ',', '.') }}</td></tr>
                @endforeach

                @if($payroll->items->where('type', 'deduction')->count() > 0)
                    <tr class="table-light"><td colspan="2" class="fw-bold">POTONGAN</td></tr>
                    @foreach($payroll->items->where('type', 'deduction') as $item)
                        <tr><td>{{ $item->name }}</td><td class="text-end text-danger">- Rp {{ number_format($item->amount, 0, ',', '.') }}</td></tr>
                    @endforeach
                @endif

                <tr class="table-success fw-bold">
                    <td>TOTAL GAJI BERSIH</td>
                    <td class="text-end fs-5">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-4 text-muted small text-center">
            Slip gaji ini dibuat secara otomatis oleh sistem HR. Untuk pertanyaan, hubungi bagian HRD.
        </div>
    </div>
</div>
@endsection
