@extends('layouts.app')
@section('title', 'Saldo Cuti')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('leaves.index') }}">Cuti</a></li>
    <li class="breadcrumb-item active">Saldo Cuti</li>
@endsection
@section('content')

{{-- Alert --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row mb-3">
    {{-- Filter tahun --}}
    <div class="col-md-6">
        <form method="GET" class="d-flex gap-2 align-items-center">
            <label class="fw-semibold mb-0">Tahun:</label>
            <select name="year" class="form-select form-select-sm" style="width:120px">
                @for($y = date('Y') + 1; $y >= date('Y') - 3; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search me-1"></i>Tampilkan</button>
        </form>
    </div>
    {{-- Aksi massal --}}
    <div class="col-md-6 text-end">
        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#initModal">
            <i class="fas fa-magic me-1"></i>Inisialisasi Massal
        </button>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus me-1"></i>Tambah Saldo
        </button>
    </div>
</div>

{{-- Tabel --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-umbrella-beach me-2"></i>Saldo Cuti Tahun {{ $year }}</span>
        <span class="badge bg-primary">{{ $employees->count() }} karyawan aktif</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="datatablesSimple">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Karyawan</th>
                        <th>Departemen</th>
                        <th class="text-center">Jatah Hari</th>
                        <th class="text-center">Digunakan</th>
                        <th class="text-center">Sisa</th>
                        <th>Progress</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $i => $employee)
                        @php $balance = $balances->get($employee->id); @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <div class="fw-semibold">{{ $employee->name }}</div>
                                <small class="text-muted">{{ $employee->nik }}</small>
                            </td>
                            <td>{{ $employee->department?->name ?? '-' }}</td>
                            @if($balance)
                                <td class="text-center fw-bold">{{ $balance->total_days }}</td>
                                <td class="text-center text-warning fw-bold">{{ $balance->used_days }}</td>
                                <td class="text-center fw-bold {{ $balance->remaining_days <= 3 ? 'text-danger' : 'text-success' }}">
                                    {{ $balance->remaining_days }}
                                </td>
                                <td style="min-width:150px;">
                                    @php $pct = $balance->total_days > 0 ? round($balance->remaining_days / $balance->total_days * 100) : 0; @endphp
                                    <div class="progress mb-1" style="height:8px;">
                                        <div class="progress-bar {{ $pct <= 25 ? 'bg-danger' : ($pct <= 50 ? 'bg-warning' : 'bg-success') }}" style="width:{{ $pct }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ $pct }}% tersisa</small>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning" title="Edit"
                                        data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id="{{ $balance->id }}"
                                        data-name="{{ $employee->name }}"
                                        data-total="{{ $balance->total_days }}"
                                        data-used="{{ $balance->used_days }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            @else
                                <td class="text-center text-muted">-</td>
                                <td class="text-center text-muted">-</td>
                                <td class="text-center"><span class="badge bg-secondary">Belum diatur</span></td>
                                <td class="text-center text-muted">-</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary" title="Tambah Saldo"
                                        data-bs-toggle="modal" data-bs-target="#addModal"
                                        data-employee-id="{{ $employee->id }}"
                                        data-employee-name="{{ $employee->name }}">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Inisialisasi Massal --}}
<div class="modal fade" id="initModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('leaves.balance.init') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-magic me-2"></i>Inisialisasi Saldo Massal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Atur saldo cuti awal untuk semua karyawan aktif yang <strong>belum memiliki saldo</strong> di tahun yang dipilih.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tahun</label>
                        <input type="number" name="year" class="form-control" value="{{ $year }}" min="2000" max="2100" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jatah Hari Cuti</label>
                        <input type="number" name="total_days" class="form-control" value="12" min="0" max="365" required>
                        <div class="form-text">Standar cuti tahunan: 12 hari kerja.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-magic me-1"></i>Inisialisasi</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Tambah Saldo --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('leaves.balance.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Tambah Saldo Cuti</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Karyawan</label>
                        <select name="employee_id" id="addEmployeeId" class="form-select" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->nik }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tahun</label>
                        <input type="number" name="year" class="form-control" value="{{ $year }}" min="2000" max="2100" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jatah Hari Cuti</label>
                        <input type="number" name="total_days" class="form-control" value="12" min="0" max="365" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Saldo --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="editForm" action="">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Saldo Cuti: <span id="editName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Total Jatah Hari</label>
                        <input type="number" name="total_days" id="editTotalDays" class="form-control" min="0" max="365" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sudah Digunakan</label>
                        <input type="number" name="used_days" id="editUsedDays" class="form-control" min="0" required>
                        <div class="form-text text-muted">Isi manual jika perlu koreksi data.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save me-1"></i>Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@section('scripts')
<script>
$(document).ready(function() {
    if ($.fn.DataTable) $('#datatablesSimple').DataTable({ language: { url: '' } });

    // Edit modal: isi data
    $('#editModal').on('show.bs.modal', function(e) {
        const btn = $(e.relatedTarget);
        $('#editName').text(btn.data('name'));
        $('#editTotalDays').val(btn.data('total'));
        $('#editUsedDays').val(btn.data('used'));
        $('#editForm').attr('action', '/leaves/balance/' + btn.data('id'));
    });

    // Add modal: pre-select karyawan jika diklik dari tombol row
    $('#addModal').on('show.bs.modal', function(e) {
        const btn = $(e.relatedTarget);
        const empId = btn.data('employee-id');
        if (empId) $('#addEmployeeId').val(empId);
    });
});
</script>
@endsection
