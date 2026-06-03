<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'date', 'clock_in', 'clock_out', 'status', 'notes',
        'clock_in_photo', 'clock_out_photo', 'overtime_hours',
    ];

    protected $casts = ['date' => 'date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getWorkingHoursAttribute()
    {
        if ($this->clock_in && $this->clock_out) {
            $in = \Carbon\Carbon::parse($this->clock_in);
            $out = \Carbon\Carbon::parse($this->clock_out);
            return $out->diffInMinutes($in) / 60;
        }
        return 0;
    }
}
