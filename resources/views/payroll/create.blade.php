@extends('layouts.app')
@section('title', 'Buat Penggajian')
@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-money-bill-wave me-2 text-primary"></i>Buat Data Penggajian</h1>
    <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>
<form action="{{ route('payroll.store') }}" method="POST" id="payrollForm">
    @csrf
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header"><i class="fas fa-user me-2"></i>Data Karyawan & Periode</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Karyawan *</label>
                            <select name="employee_id" class="form-select" required id="employeeSelect">
                                <option value="">Pilih Karyawan...</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" data-salary="{{ $emp->position?->basic_salary ?? 0 }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->name }} ({{ $emp->nik }}) - {{ $emp->position?->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bulan *</label>
                            <select name="period_month" class="form-select" required>
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ old('period_month', date('m')) == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tahun *</label>
                            <select name="period_year" class="form-select" required>
                                @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                                    <option value="{{ $y }}" {{ old('period_year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Gaji Pokok (Rp) *</label>
                            <input type="number" name="basic_salary" class="form-control" id="basicSalary" value="{{ old('basic_salary', 0) }}" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Uang Lembur (Rp)</label>
                            <input type="number" name="overtime_pay" class="form-control" value="{{ old('overtime_pay', 0) }}" min="0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items: Tunjangan/Potongan/Bonus -->
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span><i class="fas fa-list me-2"></i>Komponen Gaji</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addItem"><i class="fas fa-plus me-1"></i>Tambah</button>
                </div>
                <div class="card-body">
                    <div id="itemsContainer">
                        <div class="row g-2 mb-2 item-row">
                            <div class="col-md-3">
                                <select name="items[0][type]" class="form-select form-select-sm">
                                    <option value="allowance">Tunjangan</option>
                                    <option value="deduction">Potongan</option>
                                    <option value="bonus">Bonus</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="items[0][name]" class="form-control form-control-sm" placeholder="Nama komponen">
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="items[0][amount]" class="form-control form-control-sm" placeholder="Jumlah" min="0">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-item"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <label class="form-label fw-semibold">Catatan</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card sticky-top" style="top:80px;">
                <div class="card-header"><i class="fas fa-calculator me-2"></i>Ringkasan</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Gaji Pokok</span>
                        <span class="fw-semibold" id="sumBasic">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>+ Tunjangan & Bonus</span>
                        <span id="sumAllowance">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>+ Lembur</span>
                        <span id="sumOvertime">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-danger">
                        <span>- Potongan</span>
                        <span id="sumDeduction">Rp 0</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Gaji Bersih</span>
                        <span class="text-primary" id="sumNet">Rp 0</span>
                    </div>
                </div>
                <div class="card-footer d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1"><i class="fas fa-save me-2"></i>Simpan</button>
                    <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
let itemCount = 1;

document.getElementById('employeeSelect').addEventListener('change', function() {
    const salary = this.options[this.selectedIndex].dataset.salary || 0;
    document.getElementById('basicSalary').value = salary;
    updateSummary();
});

document.getElementById('addItem').addEventListener('click', function() {
    const container = document.getElementById('itemsContainer');
    const row = document.createElement('div');
    row.className = 'row g-2 mb-2 item-row';
    row.innerHTML = `
        <div class="col-md-3">
            <select name="items[${itemCount}][type]" class="form-select form-select-sm">
                <option value="allowance">Tunjangan</option>
                <option value="deduction">Potongan</option>
                <option value="bonus">Bonus</option>
            </select>
        </div>
        <div class="col-md-5">
            <input type="text" name="items[${itemCount}][name]" class="form-control form-control-sm" placeholder="Nama komponen">
        </div>
        <div class="col-md-3">
            <input type="number" name="items[${itemCount}][amount]" class="form-control form-control-sm item-amount" placeholder="Jumlah" min="0">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-sm btn-outline-danger remove-item"><i class="fas fa-times"></i></button>
        </div>
    `;
    container.appendChild(row);
    itemCount++;
    bindEvents();
});

function bindEvents() {
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.onclick = function() { this.closest('.item-row').remove(); updateSummary(); };
    });
    document.querySelectorAll('.item-amount, select[name*="[type]"]').forEach(el => {
        el.onchange = updateSummary;
    });
}

function formatRp(n) {
    return 'Rp ' + Math.round(n).toLocaleString('id-ID');
}

function updateSummary() {
    const basic = parseFloat(document.getElementById('basicSalary').value) || 0;
    const overtime = parseFloat(document.querySelector('[name="overtime_pay"]').value) || 0;
    let allowance = 0, deduction = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const type = row.querySelector('select')?.value;
        const amount = parseFloat(row.querySelector('[name*="[amount]"]')?.value) || 0;
        if (type === 'allowance' || type === 'bonus') allowance += amount;
        else if (type === 'deduction') deduction += amount;
    });
    const net = basic + allowance + overtime - deduction;
    document.getElementById('sumBasic').textContent = formatRp(basic);
    document.getElementById('sumAllowance').textContent = formatRp(allowance);
    document.getElementById('sumOvertime').textContent = formatRp(overtime);
    document.getElementById('sumDeduction').textContent = formatRp(deduction);
    document.getElementById('sumNet').textContent = formatRp(net);
}

document.getElementById('basicSalary').addEventListener('input', updateSummary);
document.querySelector('[name="overtime_pay"]').addEventListener('input', updateSummary);
bindEvents();
</script>
@endpush
