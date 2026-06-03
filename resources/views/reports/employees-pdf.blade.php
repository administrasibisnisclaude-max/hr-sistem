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
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 10px; }
    </style>
</head>
<body>
    <h2>Laporan Data Karyawan</h2>
    <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    <table>
        <thead><tr><th>NIK</th><th>Nama</th><th>Departemen</th><th>Jabatan</th><th>Status</th><th>Bergabung</th></tr></thead>
        <tbody>
            @foreach($employees as $emp)
                <tr>
                    <td>{{ $emp->nik }}</td>
                    <td>{{ $emp->name }}</td>
                    <td>{{ $emp->department?->name }}</td>
                    <td>{{ $emp->position?->name }}</td>
                    <td>{{ $emp->status_label }}</td>
                    <td>{{ $emp->hire_date?->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
