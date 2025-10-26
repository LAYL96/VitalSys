<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            // Vinculación directa con la cuenta del paciente (si el paciente tiene usuario propio)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Usuario “propietario/gestor” del expediente (padre/tutor/cuidador)
            // Permite que un cliente gestione dependientes (niños/adultos mayores)
            $table->foreignId('owner_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Datos del paciente
            $table->string('dpi')->nullable()->unique(); // DPI opcional pero único si existe
            $table->string('name');
            $table->string('lastname');
            $table->date('birthdate')->nullable();
            $table->enum('gender', ['Masculino', 'Femenino', 'Otro'])->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
