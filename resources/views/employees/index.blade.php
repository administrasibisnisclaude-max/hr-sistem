@extends('layouts.app')

@section('title', 'Daftar Karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item active">Karyawan</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-users me-2 text-primary"></i>Data Karyawan</h1>
    @can('create employees')
        <a href="{{ route('employees.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Karyawan
        </a>
    @endcan
</div>

<!-- Search & Filter -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('employees.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, NIK, email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="department_id" class="form-select">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="tetap" {{ request('status') === 'tetap' ? 'selected' : '' }}>Tetap</option>
                    <option value="kontrak" {{ request('status') === 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                    <option value="magang" {{ request('status') === 'magang' ? 'selected' : '' }}>Magang</option>
                    <option value="tidak_aktif" {{ request('status') === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Cari</button>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary"><i class="fas fa-redo me-1"></i>Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span>{{ $employees->total() }} karyawan ditemukan</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Departemen</th>
                    <th>Jabatan</th>
                    <th>Status</th>
                    <th>Bergabung</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                    @php $statusColors = ['tetap' => 'success', 'kontrak' => 'warning', 'magang' => 'info', 'tidak_aktif' => 'secondary']; @endphp
                    <tr>
                        <td class="fw-semibold text-primary small">{{ $employee->nik }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($employee->photo)
                                    <img src="{{ Storage::url($employee->photo) }}" class="rounded-circle me-2" style="width:32px;height:32px;object-fit:cover;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width:32px;height:32px;font-size:0.75rem;font-weight:700;">
                                        {{ strtoupper(substr($employee->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $employee->name }}</div>
                                    <div class="text-muted small">{{ $employee->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $employee->department?->name ?? '-' }}</td>
                        <td>{{ $employee->position?->name ?? '-' }}</td>
                        <td><span class="badge bg-{{ $statusColors[$employee->employment_status] ?? 'secondary' }}">{{ $employee->status_label }}</span></td>
                        <td class="small">{{ $employee->hire_date?->format('d/m/Y') ?? '-' }}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('employees.show', $employee) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('edit employees')
                                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                                @can('delete employees')
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus karyawan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-users fa-2x mb-2 d-block opacity-25"></i>Tidak ada data karyawan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($employees->hasPages())
        <div class="card-footer">{{ $employees->links() }}</div>
    @endif
</div>
@endsection
