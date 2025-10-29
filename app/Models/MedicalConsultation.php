<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalConsultation extends Model
{
    use HasFactory;

    // Nombre de tabla explícito (por si tu convención difiere)
    protected $table = 'medical_consultations';

    protected $fillable = [
        'appointment_id',
        'patient_id',
        'doctor_id',
        'reason',
        'diagnosis',
        'prescription',
        'temperature',
        'pulse',
        'pressure',
        'weight',
    ];

    /* =========================
       Relaciones
    ========================== */

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /* =========================
       Scopes útiles (opcionales)
    ========================== */

    public function scopeForDoctor($query, int $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeForPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId);
    }
}
