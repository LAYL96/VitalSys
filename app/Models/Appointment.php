<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'date',
        'time',
        'status',
        'notes',
    ];

    // Relación con el paciente
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Relación con el médico (usuario)
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
