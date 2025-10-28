<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Fuerza el guard usado por Spatie/Permission para este modelo.
     * Debe coincidir con el guard por defecto de tu auth web.
     */
    protected string $guard_name = 'web';

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Atributos ocultos para arrays/JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts de atributos.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación: citas donde el usuario actúa como médico.
     */
    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /**
     * Hook de modelo para asignar rol por defecto "Cliente" al crearse.
     * Usa este enfoque SOLO si no empleas el listener de evento Registered.
     */
    protected static function booted(): void
    {
        static::created(function (User $user): void {
            // Evita doble asignación si el usuario ya tiene algún rol
            if (! $user->hasAnyRole(['Administrador', 'Médico', 'Empleado', 'Cliente'])) {
                // Asigna el rol por defecto
                $user->assignRole('Cliente');
            }
        });
    }
}
