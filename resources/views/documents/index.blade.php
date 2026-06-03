@extends('layouts.app')
@section('title', 'Dokumen Karyawan')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-folder-open me-2 text-primary"></i>Dokumen Karyawan</h1>
    <a href="{{ route('documents.create') }}" class="btn btn-primary"><i class="fas fa-upload me-2"></i>Upload Dokumen</a>
</div>
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <select name="employee_id" class="form-select">
                    <option value="">Semua Karyawan</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="document_type" class="form-select">
                    <option value="">Semua Jenis</option>
                    @foreach(['ktp' => 'KTP', 'npwp' => 'NPWP', 'ijazah' => 'Ijazah', 'cv' => 'CV', 'foto' => 'Foto', 'kontrak' => 'Kontrak', 'lainnya' => 'Lainnya'] as $val => $label)
                        <option value="{{ $val }}" {{ request('document_type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Filter</button>
                <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary ms-1">Reset</a>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Karyawan</th><th>Jenis Dokumen</th><th>Nama File</th><th>Ukuran</th><th>Diunggah Oleh</th><th>Tanggal</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
                @forelse($documents as $doc)
                    <tr>
                        <td class="fw-semibold">{{ $doc->employee->name }}</td>
                        <td><span class="badge bg-secondary">{{ $doc->type_label }}</span></td>
                        <td class="small">{{ $doc->file_name }}</td>
                        <td class="small">{{ $doc->file_size ? number_format($doc->file_size / 1024, 1) . ' KB' : '-' }}</td>
                        <td class="small">{{ $doc->uploader?->name ?? '-' }}</td>
                        <td class="small">{{ $doc->created_at->format('d/m/Y') }}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('documents.download', $doc) }}" class="btn btn-outline-primary"><i class="fas fa-download"></i></a>
                                <form action="{{ route('documents.destroy', $doc) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dokumen ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada dokumen</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($documents->hasPages())<div class="card-footer">{{ $documents->links() }}</div>@endif
</div>
@endsection
