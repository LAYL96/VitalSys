<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_schedule_overrides', function (Blueprint $table) {
            $table->id();

            // Médico al que aplica la excepción
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');

            // Fecha específica del override (única por doctor)
            $table->date('date');

            // Franja opcional que reemplaza la base (si null y active=true, podrías interpretar "usar base")
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            // Tamaño del slot opcional para ese día
            $table->unsignedSmallInteger('slot_minutes')->nullable();

            // Activo/inactivo para ese día (false = día libre)
            $table->boolean('active')->default(true);

            // Nota opcional
            $table->string('note')->nullable();

            $table->timestamps();

            // Evita duplicados por doctor/fecha
            $table->unique(['doctor_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_schedule_overrides');
    }
};
