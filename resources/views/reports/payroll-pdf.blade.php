<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        h2 { color: #3b5998; }
        table { width: 100%; border-collapse: collapse; }
        table th { background: #3b5998; color: white; padding: 6px 8px; text-align: left; }
        table td { padding: 5px 8px; border-bottom: 1px solid #e0e4ec; }
        td.text-right { text-align: right; }
        .total { font-weight: bold; background: #e9f5e9; }
    </style>
</head>
<body>
    <h2>Laporan Penggajian - {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}</h2>
    <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    <p>Total Gaji Bersih: <strong>Rp {{ number_format($totalNet, 0, ',', '.') }}</strong></p>
    <table>
        <thead><tr><th>NIK</th><th>Nama</th><th>Dept.</th><th>Gaji Pokok</th><th>Tunjangan</th><th>Potongan</th><th>Gaji Bersih</th></tr></thead>
        <tbody>
            @foreach($payrolls as $p)
                <tr>
                    <td>{{ $p->employee->nik }}</td>
                    <td>{{ $p->employee->name }}</td>
                    <td>{{ $p->employee->department?->name }}</td>
                    <td class="text-right">{{ number_format($p->basic_salary, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($p->total_allowance + $p->bonus, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($p->total_deduction, 0, ',', '.') }}</td>
                    <td class="text-right"><strong>{{ number_format($p->net_salary, 0, ',', '.') }}</strong></td>
                </tr>
            @endforeach
            <tr class="total">
                <td colspan="6" class="text-right">TOTAL:</td>
                <td class="text-right">Rp {{ number_format($totalNet, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
