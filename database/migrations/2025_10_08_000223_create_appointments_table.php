<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Paciente atendido (expediente)
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');

            // Médico que atenderá (usuario con rol "Médico")
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');

            // Usuario que hizo la reserva (puede ser el mismo paciente u otra persona)
            $table->foreignId('booked_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('date');
            $table->time('time');
            $table->string('status')->default('pendiente'); // pendiente, completada, cancelada
            $table->string('reason')->nullable();           // Motivo de la cita (opcional)
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
