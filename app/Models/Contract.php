<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'contract_type', 'start_date', 'end_date', 'status', 'notes', 'file_path',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getDaysUntilExpiryAttribute()
    {
        if ($this->end_date) {
            return now()->diffInDays($this->end_date, false);
        }
        return null;
    }

    public function getIsExpiringSoonAttribute()
    {
        $days = $this->days_until_expiry;
        return $days !== null && $days >= 0 && $days <= 30;
    }
}
