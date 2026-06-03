@extends('layouts.app')
@section('title', 'Jabatan')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-briefcase me-2 text-primary"></i>Jabatan</h1>
    <a href="{{ route('positions.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Tambah Jabatan</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>No</th><th>Nama Jabatan</th><th>Departemen</th><th>Gaji Pokok</th><th>Jumlah Karyawan</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
                @forelse($positions as $pos)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $pos->name }}</td>
                        <td>{{ $pos->department?->name ?? '-' }}</td>
                        <td>Rp {{ number_format($pos->basic_salary, 0, ',', '.') }}</td>
                        <td><span class="badge bg-primary">{{ $pos->employees_count }}</span></td>
                        <td><span class="badge bg-{{ $pos->is_active ? 'success' : 'secondary' }}">{{ $pos->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('positions.edit', $pos) }}" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('positions.destroy', $pos) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jabatan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data jabatan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($positions->hasPages())<div class="card-footer">{{ $positions->links() }}</div>@endif
</div>
@endsection
