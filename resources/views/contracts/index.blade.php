@extends('layouts.app')
@section('title', 'Kontrak Kerja')
@section('breadcrumb')
    <li class="breadcrumb-item active">Kontrak</li>
@endsection
@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('contracts.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Tambah Kontrak</a>
</div>

<div class="card mb-4">
    <div class="card-header"><i class="fas fa-filter me-2"></i>Filter</div>
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                    <option value="terminated" {{ request('status') === 'terminated' ? 'selected' : '' }}>Berakhir</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-check mt-1">
                    <input class="form-check-input" type="checkbox" name="expiring" id="expiring" value="1" {{ request('expiring') ? 'checked' : '' }}>
                    <label class="form-check-label small" for="expiring">Hampir habis</label>
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Filter</button>
                <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary ms-1">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="datatablesSimple">
                <thead><tr><th>Karyawan</th><th>Tipe</th><th>Mulai</th><th>Berakhir</th><th>Status</th><th>Sisa Hari</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($contracts as $contract)
                        <tr class="{{ $contract->is_expiring_soon ? 'table-warning' : '' }}">
                            <td><div class="fw-semibold">{{ $contract->employee->name }}</div><small class="text-muted">{{ $contract->employee->department?->name }}</small></td>
                            <td><span class="badge bg-secondary">{{ ucfirst($contract->contract_type) }}</span></td>
                            <td>{{ $contract->start_date->format('d/m/Y') }}</td>
                            <td>{{ $contract->end_date?->format('d/m/Y') ?? 'Tidak ada' }}</td>
                            <td>
                                @php $statusColors = ['active' => 'success', 'expired' => 'warning', 'terminated' => 'danger']; @endphp
                                <span class="badge bg-{{ $statusColors[$contract->status] ?? 'secondary' }}">{{ ucfirst($contract->status) }}</span>
                            </td>
                            <td>
                                @if($contract->end_date && $contract->status === 'active')
                                    @php $days = $contract->days_until_expiry; @endphp
                                    @if($days < 0) <span class="text-danger small">Kadaluarsa</span>
                                    @elseif($days <= 30) <span class="badge bg-danger">{{ $days }} hari</span>
                                    @else <span class="text-muted small">{{ $days }} hari</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('contracts.destroy', $contract) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kontrak ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data kontrak</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($contracts->hasPages())<div class="mt-3">{{ $contracts->links() }}</div>@endif
    </div>
</div>
@endsection
@section('scripts')
<script>$(document).ready(function() { if ($.fn.DataTable) $('#datatablesSimple').DataTable(); });</script>
@endsection
