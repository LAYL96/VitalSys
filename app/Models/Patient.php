<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        // Identificadores de propietario / cuenta
        'user_id',
        'owner_user_id',

        // Datos del paciente
        'dpi',
        'name',
        'lastname',
        'birthdate',
        'gender',
        'phone',
        'email',
        'address',
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    // (Opcional) dueños
    public function user()
    {
        return $this->belongsTo(User::class); // si el paciente tiene cuenta propia
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id'); // quien gestiona (padre/tutor)
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function consultations()
    {
        return $this->hasMany(\App\Models\MedicalConsultation::class);
    }
}
