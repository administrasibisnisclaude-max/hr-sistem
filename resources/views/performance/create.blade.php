@extends('layouts.app')
@section('title', 'Tambah Evaluasi Kinerja')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-chart-bar me-2 text-primary"></i>Tambah Evaluasi Kinerja</h1>
    <a href="{{ route('performance.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>
<div class="card" style="max-width:700px;">
    <div class="card-body">
        <form action="{{ route('performance.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Karyawan *</label>
                    <select name="employee_id" class="form-select" required>
                        <option value="">Pilih Karyawan...</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Periode *</label>
                    <input type="text" name="period" class="form-control" placeholder="Contoh: 2024-Q1, Jan 2024" value="{{ old('period') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Skor (0-100) *</label>
                    <input type="number" name="score" class="form-control" min="0" max="100" step="0.01" value="{{ old('score') }}" required id="scoreInput">
                    <div class="mt-2">
                        <div class="progress" style="height:10px;">
                            <div class="progress-bar" id="scoreBar" style="width:0%"></div>
                        </div>
                        <small class="text-muted">Nilai: <span id="gradePreview">-</span></small>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Catatan</label>
                    <textarea name="notes" class="form-control" rows="4" placeholder="Catatan evaluasi, kelebihan, kekurangan...">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
                <a href="{{ route('performance.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('scoreInput').addEventListener('input', function() {
    const score = parseFloat(this.value) || 0;
    const bar = document.getElementById('scoreBar');
    const preview = document.getElementById('gradePreview');
    bar.style.width = score + '%';
    let grade, color;
    if (score >= 90) { grade = 'A (Sangat Baik)'; color = 'bg-success'; }
    else if (score >= 80) { grade = 'B (Baik)'; color = 'bg-primary'; }
    else if (score >= 70) { grade = 'C (Cukup)'; color = 'bg-warning'; }
    else if (score >= 60) { grade = 'D (Kurang)'; color = 'bg-secondary'; }
    else { grade = 'E (Sangat Kurang)'; color = 'bg-danger'; }
    bar.className = 'progress-bar ' + color;
    preview.textContent = grade;
});
</script>
@endpush
