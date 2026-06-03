@extends('layouts.app')

@section('title', $employee->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Karyawan</a></li>
    <li class="breadcrumb-item active">{{ $employee->name }}</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-user me-2 text-primary"></i>Detail Karyawan</h1>
    <div class="d-flex gap-2">
        @can('edit employees')
            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
        @endcan
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row g-3">
    <!-- Profile Card -->
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body text-center py-4">
                @if($employee->photo)
                    <img src="{{ Storage::url($employee->photo) }}" class="rounded-circle mb-3" style="width:100px;height:100px;object-fit:cover;border:3px solid #e0e4ec;">
                @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width:100px;height:100px;font-size:2rem;font-weight:700;">
                        {{ strtoupper(substr($employee->name, 0, 1)) }}
                    </div>
                @endif
                <h5 class="fw-bold">{{ $employee->name }}</h5>
                <p class="text-muted small mb-1">{{ $employee->position?->name ?? '-' }}</p>
                <p class="text-muted small mb-3">{{ $employee->department?->name ?? '-' }}</p>
                @php $statusColors = ['tetap' => 'success', 'kontrak' => 'warning', 'magang' => 'info', 'tidak_aktif' => 'secondary']; @endphp
                <span class="badge bg-{{ $statusColors[$employee->employment_status] ?? 'secondary' }} px-3 py-2">{{ $employee->status_label }}</span>
            </div>
            <div class="card-footer text-center small text-muted">
                NIK: <strong>{{ $employee->nik }}</strong>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <!-- Personal Info -->
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-user me-2"></i>Data Pribadi</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Email</small>
                        <span>{{ $employee->email }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">No. Telepon</small>
                        <span>{{ $employee->phone ?? '-' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Jenis Kelamin</small>
                        <span>{{ $employee->gender_label ?? '-' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Tanggal Lahir</small>
                        <span>{{ $employee->birth_date?->format('d/m/Y') ?? '-' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">NPWP</small>
                        <span>{{ $employee->npwp ?? '-' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Kontak Darurat</small>
                        <span>{{ $employee->emergency_contact ?? '-' }}</span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Alamat</small>
                        <span>{{ $employee->address ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Work Info -->
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-briefcase me-2"></i>Data Kepegawaian</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Tanggal Masuk</small>
                        <span>{{ $employee->hire_date?->format('d/m/Y') ?? '-' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Gaji Pokok (Jabatan)</small>
                        <span>Rp {{ number_format($employee->position?->basic_salary ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Bank / No. Rekening</small>
                        <span>{{ $employee->bank_name ?? '-' }} / {{ $employee->bank_account ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs: Attendance, Leave, Payroll, Contract, Documents, Performance -->
        <div class="card">
            <div class="card-header p-0">
                <ul class="nav nav-tabs" id="empTabs">
                    <li class="nav-item"><a class="nav-link active px-3 py-3" data-bs-toggle="tab" href="#tabAttendance"><i class="fas fa-clock me-1"></i>Absensi</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-3" data-bs-toggle="tab" href="#tabLeave"><i class="fas fa-calendar me-1"></i>Cuti</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-3" data-bs-toggle="tab" href="#tabPayroll"><i class="fas fa-money-bill me-1"></i>Gaji</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-3" data-bs-toggle="tab" href="#tabContract"><i class="fas fa-file-contract me-1"></i>Kontrak</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-3" data-bs-toggle="tab" href="#tabDocs"><i class="fas fa-folder me-1"></i>Dokumen</a></li>
                    <li class="nav-item"><a class="nav-link px-3 py-3" data-bs-toggle="tab" href="#tabPerf"><i class="fas fa-star me-1"></i>Evaluasi</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <!-- Attendance Tab -->
                <div class="tab-pane fade show active" id="tabAttendance">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Tanggal</th><th>Masuk</th><th>Pulang</th><th>Status</th></tr></thead>
                            <tbody>
                                @forelse($recentAttendances as $att)
                                    <tr>
                                        <td>{{ $att->date->format('d/m/Y') }}</td>
                                        <td>{{ $att->clock_in ?? '-' }}</td>
                                        <td>{{ $att->clock_out ?? '-' }}</td>
                                        <td>
                                            @php $attColors = ['hadir' => 'success', 'izin' => 'warning', 'sakit' => 'info', 'alpha' => 'danger', 'cuti' => 'primary']; @endphp
                                            <span class="badge bg-{{ $attColors[$att->status] ?? 'secondary' }}">{{ ucfirst($att->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data absensi</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Leave Tab -->
                <div class="tab-pane fade" id="tabLeave">
                    @if($leaveBalance)
                        <div class="p-3 border-bottom">
                            <div class="row text-center">
                                <div class="col-4"><div class="fw-bold fs-4">{{ $leaveBalance->total_days }}</div><small class="text-muted">Total</small></div>
                                <div class="col-4"><div class="fw-bold fs-4 text-warning">{{ $leaveBalance->used_days }}</div><small class="text-muted">Digunakan</small></div>
                                <div class="col-4"><div class="fw-bold fs-4 text-success">{{ $leaveBalance->remaining_days }}</div><small class="text-muted">Sisa</small></div>
                            </div>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Jenis</th><th>Periode</th><th>Hari</th><th>Status</th></tr></thead>
                            <tbody>
                                @forelse($recentLeaves as $leave)
                                    <tr>
                                        <td>{{ $leave->leave_type_label }}</td>
                                        <td>{{ $leave->start_date->format('d/m') }} - {{ $leave->end_date->format('d/m/Y') }}</td>
                                        <td>{{ $leave->days }}</td>
                                        <td>{!! $leave->status_badge !!}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data cuti</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payroll Tab -->
                <div class="tab-pane fade" id="tabPayroll">
                    @if($latestPayroll)
                        <div class="p-3">
                            <div class="row g-2 text-center mb-3">
                                <div class="col-4"><small class="text-muted d-block">Gaji Bersih</small><span class="fw-bold">Rp {{ number_format($latestPayroll->net_salary, 0, ',', '.') }}</span></div>
                                <div class="col-4"><small class="text-muted d-block">Periode</small><span class="fw-bold">{{ $latestPayroll->period_label }}</span></div>
                                <div class="col-4"><small class="text-muted d-block">Status</small>
                                    <span class="badge bg-{{ ['draft' => 'secondary', 'approved' => 'primary', 'paid' => 'success'][$latestPayroll->status] ?? 'secondary' }}">{{ ucfirst($latestPayroll->status) }}</span>
                                </div>
                            </div>
                            <a href="{{ route('payroll.show', $latestPayroll) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">Belum ada data penggajian</div>
                    @endif
                </div>

                <!-- Contract Tab -->
                <div class="tab-pane fade" id="tabContract">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Tipe</th><th>Mulai</th><th>Berakhir</th><th>Status</th></tr></thead>
                            <tbody>
                                @forelse($employee->contracts as $contract)
                                    <tr>
                                        <td>{{ ucfirst($contract->contract_type) }}</td>
                                        <td>{{ $contract->start_date->format('d/m/Y') }}</td>
                                        <td>{{ $contract->end_date?->format('d/m/Y') ?? 'Tidak ada' }}</td>
                                        <td>
                                            @php $contractColors = ['active' => 'success', 'expired' => 'warning', 'terminated' => 'danger']; @endphp
                                            <span class="badge bg-{{ $contractColors[$contract->status] ?? 'secondary' }}">{{ ucfirst($contract->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data kontrak</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Documents Tab -->
                <div class="tab-pane fade" id="tabDocs">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Jenis</th><th>Nama File</th><th>Diunggah</th><th>Aksi</th></tr></thead>
                            <tbody>
                                @forelse($employee->documents as $doc)
                                    <tr>
                                        <td><span class="badge bg-secondary">{{ $doc->type_label }}</span></td>
                                        <td>{{ $doc->file_name }}</td>
                                        <td>{{ $doc->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('documents.download', $doc) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada dokumen</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Performance Tab -->
                <div class="tab-pane fade" id="tabPerf">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Periode</th><th>Skor</th><th>Nilai</th><th>Catatan</th></tr></thead>
                            <tbody>
                                @forelse($performances as $perf)
                                    <tr>
                                        <td>{{ $perf->period }}</td>
                                        <td><strong>{{ $perf->score }}</strong></td>
                                        <td>
                                            @php $gradeColors = ['A' => 'success', 'B' => 'primary', 'C' => 'warning', 'D' => 'orange', 'E' => 'danger']; @endphp
                                            <span class="badge bg-{{ $gradeColors[$perf->grade] ?? 'secondary' }}">{{ $perf->grade }}</span>
                                        </td>
                                        <td class="small">{{ Str::limit($perf->notes, 50) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data evaluasi</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
