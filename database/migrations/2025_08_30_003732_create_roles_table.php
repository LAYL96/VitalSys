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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // administrador, empleado, medico, cliente
            $table->timestamps();
        });

        // Agregar relación en users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->default(4)->constrained('roles');
            // 4 será Cliente por defecto
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
