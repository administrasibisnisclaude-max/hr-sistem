@extends('layouts.app')
@section('title', 'Ajukan Cuti')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-calendar-plus me-2 text-primary"></i>Ajukan Cuti</h1>
    <a href="{{ route('leaves.own') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

@if($leaveBalance)
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i>
        Saldo cuti tahunan Anda: <strong>{{ $leaveBalance->remaining_days }}</strong> hari tersisa dari {{ $leaveBalance->total_days }} hari.
    </div>
@endif

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form action="{{ route('leaves.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Jenis Cuti *</label>
                <select name="leave_type" class="form-select" required>
                    <option value="tahunan" {{ old('leave_type') === 'tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                    <option value="sakit" {{ old('leave_type') === 'sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                    <option value="melahirkan" {{ old('leave_type') === 'melahirkan' ? 'selected' : '' }}>Cuti Melahirkan</option>
                    <option value="darurat" {{ old('leave_type') === 'darurat' ? 'selected' : '' }}>Cuti Darurat</option>
                    <option value="lainnya" {{ old('leave_type') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Mulai *</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}" required id="startDate">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Selesai *</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}" required id="endDate">
                </div>
            </div>
            <div class="mb-3">
                <div class="alert alert-secondary py-2 small" id="daysCount">Pilih tanggal untuk menghitung jumlah hari</div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Alasan *</label>
                <textarea name="reason" class="form-control" rows="3" required>{{ old('reason') }}</textarea>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Lampiran (opsional)</label>
                <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.png">
                <small class="text-muted">PDF/JPG/PNG, maks 5MB</small>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-2"></i>Ajukan Cuti</button>
                <a href="{{ route('leaves.own') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function calcDays() {
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    if (start && end) {
        const diff = Math.floor((new Date(end) - new Date(start)) / (1000*60*60*24)) + 1;
        if (diff > 0) {
            document.getElementById('daysCount').textContent = `Total: ${diff} hari`;
            document.getElementById('daysCount').className = 'alert alert-info py-2 small';
        } else {
            document.getElementById('daysCount').textContent = 'Tanggal selesai harus setelah tanggal mulai';
            document.getElementById('daysCount').className = 'alert alert-danger py-2 small';
        }
    }
}
document.getElementById('startDate').addEventListener('change', calcDays);
document.getElementById('endDate').addEventListener('change', calcDays);
</script>
@endpush
