<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #3b5998; padding-bottom: 15px; margin-bottom: 20px; }
        .company-name { font-size: 18px; font-weight: bold; color: #3b5998; }
        .slip-title { text-align: right; font-size: 16px; font-weight: bold; color: #3b5998; }
        .info-table { width: 100%; background: #f0f4f8; padding: 10px; margin-bottom: 20px; }
        .info-table td { padding: 5px 10px; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 8px 10px; border-bottom: 1px solid #e0e4ec; }
        table th { background: #f8f9fc; font-weight: bold; }
        .section-header { background: #e9eef5 !important; font-weight: bold; }
        .total-row { background: #d4edda !important; font-weight: bold; font-size: 14px; }
        .text-right { text-align: right; }
        .text-danger { color: #dc3545; }
        .text-success { color: #198754; }
        .footer { margin-top: 30px; text-align: center; color: #888; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="company-name">{{ $company->get('company_name')?->value ?? 'Perusahaan' }}</div>
            <div>{{ $company->get('company_address')?->value }}</div>
            <div>{{ $company->get('company_phone')?->value }}</div>
        </div>
        <div>
            <div class="slip-title">SLIP GAJI</div>
            <div style="text-align:right;">Periode: {{ $payroll->period_label }}</div>
        </div>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Nama:</strong> {{ $payroll->employee->name }}</td>
            <td><strong>NIK:</strong> {{ $payroll->employee->nik }}</td>
        </tr>
        <tr>
            <td><strong>Jabatan:</strong> {{ $payroll->employee->position?->name ?? '-' }}</td>
            <td><strong>Departemen:</strong> {{ $payroll->employee->department?->name ?? '-' }}</td>
        </tr>
    </table>

    <table>
        <thead><tr><th>Keterangan</th><th class="text-right">Jumlah</th></tr></thead>
        <tbody>
            <tr class="section-header"><td colspan="2">PENGHASILAN</td></tr>
            <tr><td>Gaji Pokok</td><td class="text-right">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td></tr>
            @if($payroll->overtime_pay > 0)
                <tr><td>Uang Lembur</td><td class="text-right">Rp {{ number_format($payroll->overtime_pay, 0, ',', '.') }}</td></tr>
            @endif
            @foreach($payroll->items->where('type', 'allowance') as $item)
                <tr><td>{{ $item->name }}</td><td class="text-right text-success">Rp {{ number_format($item->amount, 0, ',', '.') }}</td></tr>
            @endforeach
            @foreach($payroll->items->where('type', 'bonus') as $item)
                <tr><td>{{ $item->name }} (Bonus)</td><td class="text-right text-success">Rp {{ number_format($item->amount, 0, ',', '.') }}</td></tr>
            @endforeach
            @if($payroll->items->where('type', 'deduction')->count() > 0)
                <tr class="section-header"><td colspan="2">POTONGAN</td></tr>
                @foreach($payroll->items->where('type', 'deduction') as $item)
                    <tr><td>{{ $item->name }}</td><td class="text-right text-danger">- Rp {{ number_format($item->amount, 0, ',', '.') }}</td></tr>
                @endforeach
            @endif
            <tr class="total-row">
                <td>TOTAL GAJI BERSIH</td>
                <td class="text-right">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh sistem HR. {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
