@extends('layouts.app')
@section('title', 'Evaluasi Kinerja')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-chart-bar me-2 text-primary"></i>Evaluasi Kinerja</h1>
    @can('manage performance')
        <a href="{{ route('performance.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Tambah Evaluasi</a>
    @endcan
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
                <input type="text" name="period" class="form-control" placeholder="Cari periode..." value="{{ request('period') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Cari</button>
                <a href="{{ route('performance.index') }}" class="btn btn-outline-secondary ms-1">Reset</a>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Karyawan</th><th>Departemen</th><th>Periode</th><th>Skor</th><th>Nilai</th><th>Evaluator</th><th>Tanggal</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
                @forelse($evaluations as $eval)
                    @php $gradeColors = ['A' => 'success', 'B' => 'primary', 'C' => 'warning', 'D' => 'secondary', 'E' => 'danger']; @endphp
                    <tr>
                        <td class="fw-semibold">{{ $eval->employee->name }}</td>
                        <td>{{ $eval->employee->department?->name ?? '-' }}</td>
                        <td>{{ $eval->period }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:6px;width:80px;">
                                    <div class="progress-bar bg-{{ $gradeColors[$eval->grade] ?? 'secondary' }}" style="width:{{ $eval->score }}%"></div>
                                </div>
                                <span class="fw-bold">{{ $eval->score }}</span>
                            </div>
                        </td>
                        <td><span class="badge bg-{{ $gradeColors[$eval->grade] ?? 'secondary' }} px-3">{{ $eval->grade }}</span></td>
                        <td>{{ $eval->evaluator->name }}</td>
                        <td>{{ $eval->created_at->format('d/m/Y') }}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('performance.show', $eval) }}" class="btn btn-outline-info"><i class="fas fa-eye"></i></a>
                                @can('manage performance')
                                    <a href="{{ route('performance.edit', $eval) }}" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('performance.destroy', $eval) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus evaluasi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data evaluasi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($evaluations->hasPages())<div class="card-footer">{{ $evaluations->links() }}</div>@endif
</div>
@endsection
