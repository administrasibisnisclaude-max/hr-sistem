<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        h2 { color: #3b5998; }
        table { width: 100%; border-collapse: collapse; }
        table th { background: #3b5998; color: white; padding: 6px 8px; text-align: center; }
        table td { padding: 5px 8px; border-bottom: 1px solid #e0e4ec; text-align: center; }
        table td:first-child, table td:nth-child(2) { text-align: left; }
    </style>
</head>
<body>
    <h2>Laporan Absensi - {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}</h2>
    <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    <table>
        <thead><tr><th>Nama</th><th>Dept.</th><th>Hadir</th><th>Izin</th><th>Sakit</th><th>Alpha</th><th>Cuti</th></tr></thead>
        <tbody>
            @foreach($employees as $emp)
                <tr>
                    <td>{{ $emp->name }}</td>
                    <td>{{ $emp->department?->name }}</td>
                    <td>{{ $emp->attendances->where('status', 'hadir')->count() }}</td>
                    <td>{{ $emp->attendances->where('status', 'izin')->count() }}</td>
                    <td>{{ $emp->attendances->where('status', 'sakit')->count() }}</td>
                    <td>{{ $emp->attendances->where('status', 'alpha')->count() }}</td>
                    <td>{{ $emp->attendances->where('status', 'cuti')->count() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
