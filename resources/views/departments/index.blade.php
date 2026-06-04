@extends('layouts.app')
@section('title', 'Departemen')
@section('breadcrumb')
    <li class="breadcrumb-item active">Departemen</li>
@endsection
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <span></span>
    <a href="{{ route('departments.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Tambah Departemen</a>
</div>
<div class="card mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="datatablesSimple">
                <thead><tr><th>No</th><th>Nama Departemen</th><th>Deskripsi</th><th>Jumlah Karyawan</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($departments as $dept)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $dept->name }}</td>
                            <td class="text-muted small">{{ $dept->description ?? '-' }}</td>
                            <td><span class="badge bg-primary">{{ $dept->employees_count }}</span></td>
                            <td><span class="badge bg-{{ $dept->is_active ? 'success' : 'secondary' }}">{{ $dept->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('departments.edit', $dept) }}" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('departments.destroy', $dept) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus departemen ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data departemen</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($departments->hasPages())<div class="mt-3">{{ $departments->links() }}</div>@endif
    </div>
</div>
@endsection
@section('scripts')
<script>$(document).ready(function() { if ($.fn.DataTable) $('#datatablesSimple').DataTable(); });</script>
@endsection
