<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'dpi',
        'name',
        'lastname',
        'birthdate',
        'phone',
        'email',
        'address',
    ];

    // Relación con las citas médicas
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
