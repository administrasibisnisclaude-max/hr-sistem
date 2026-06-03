<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'document_type', 'file_path', 'file_name', 'file_size', 'uploaded_by', 'notes'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getTypeLabelAttribute()
    {
        return match ($this->document_type) {
            'ktp' => 'KTP',
            'npwp' => 'NPWP',
            'ijazah' => 'Ijazah',
            'cv' => 'CV',
            'foto' => 'Foto',
            'kontrak' => 'Kontrak',
            'lainnya' => 'Lainnya',
            default => $this->document_type,
        };
    }
}
