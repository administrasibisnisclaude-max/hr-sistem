@extends('layouts.app')
@section('title', 'Detail Evaluasi')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-chart-bar me-2 text-primary"></i>Detail Evaluasi Kinerja</h1>
    <a href="{{ route('performance.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>
<div class="card" style="max-width:600px;">
    <div class="card-body">
        @php $gradeColors = ['A' => 'success', 'B' => 'primary', 'C' => 'warning', 'D' => 'secondary', 'E' => 'danger']; @endphp
        <div class="text-center mb-4">
            <div class="display-2 fw-bold text-{{ $gradeColors[$performance->grade] ?? 'secondary' }}">{{ $performance->grade }}</div>
            <div class="fs-4 fw-bold">{{ $performance->score }}/100</div>
            <div class="progress mt-2" style="height:12px;max-width:300px;margin:0 auto;">
                <div class="progress-bar bg-{{ $gradeColors[$performance->grade] ?? 'secondary' }}" style="width:{{ $performance->score }}%"></div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <small class="text-muted d-block">Karyawan</small>
                <strong>{{ $performance->employee->name }}</strong>
                <div class="small text-muted">{{ $performance->employee->department?->name }}</div>
            </div>
            <div class="col-md-6">
                <small class="text-muted d-block">Periode</small>
                <strong>{{ $performance->period }}</strong>
            </div>
            <div class="col-md-6">
                <small class="text-muted d-block">Evaluator</small>
                <strong>{{ $performance->evaluator->name }}</strong>
            </div>
            <div class="col-md-6">
                <small class="text-muted d-block">Tanggal Evaluasi</small>
                <strong>{{ $performance->created_at->format('d/m/Y') }}</strong>
            </div>
            @if($performance->notes)
                <div class="col-12">
                    <small class="text-muted d-block">Catatan</small>
                    <div class="p-3 bg-light rounded">{{ $performance->notes }}</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
