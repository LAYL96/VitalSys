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
        Schema::create('medical_consultations', function (Blueprint $table) {
            $table->id();
            // Llaves
            $table->foreignId('appointment_id')->constrained('appointments')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();

            // Datos clínicos básicos
            $table->text('reason')->nullable();        // motivo / síntoma principal
            $table->text('diagnosis')->nullable();     // diagnóstico
            $table->text('prescription')->nullable();  // receta (texto simple por ahora)

            // Signos vitales opcionales
            $table->string('temperature')->nullable(); // ej: "36.8 °C"
            $table->string('pulse')->nullable();       // ej: "72 lpm"
            $table->string('pressure')->nullable();    // ej: "120/80"
            $table->string('weight')->nullable();      // ej: "70 kg"
            $table->timestamps();
            $table->unique('appointment_id'); // 1 registro clínico por cita
            $table->index(['patient_id', 'doctor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_consultations');
    }
};
