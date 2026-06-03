<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Employee extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'nik', 'name', 'email', 'phone', 'address', 'birth_date', 'gender',
        'hire_date', 'department_id', 'position_id', 'employment_status',
        'photo', 'bank_name', 'bank_account', 'npwp', 'emergency_contact',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hire_date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->setDescriptionForEvent(fn(string $eventName) => "Employee {$eventName}");
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function performances()
    {
        return $this->hasMany(PerformanceEvaluation::class);
    }

    public function getGenderLabelAttribute()
    {
        return $this->gender === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->employment_status) {
            'tetap' => 'Karyawan Tetap',
            'kontrak' => 'Karyawan Kontrak',
            'magang' => 'Magang',
            'tidak_aktif' => 'Tidak Aktif',
            default => $this->employment_status,
        };
    }

    public function getActiveContractAttribute()
    {
        return $this->contracts()->where('status', 'active')->latest()->first();
    }

    public function getCurrentLeaveBalanceAttribute()
    {
        return $this->leaveBalances()->where('year', date('Y'))->first();
    }

    public function scopeActive($query)
    {
        return $query->where('employment_status', '!=', 'tidak_aktif');
    }
}
