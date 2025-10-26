<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            // Médico (usuario con rol Médico)
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();

            // Día de la semana: 0=Dom, 1=Lun, ..., 6=Sáb
            $table->unsignedTinyInteger('day_of_week');

            // Horario del turno
            $table->time('start_time'); // ej. 09:00
            $table->time('end_time');   // ej. 17:00

            // Duración de cada cita en minutos (default 30)
            $table->unsignedSmallInteger('slot_minutes')->default(30);

            // Activo/inactivo para poder deshabilitar un turno sin borrarlo
            $table->boolean('active')->default(true);
            $table->timestamps();
            // Índices útiles para consultas por médico y día
            $table->index(['doctor_id', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
