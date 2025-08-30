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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del producto
            $table->string('sku')->unique(); // Código único del producto

            // Llaves foráneas definidas explícitamente
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');

            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');

            $table->text('description')->nullable(); // Descripción
            $table->string('image')->nullable(); // Imagen del producto
            $table->decimal('price', 10, 2); // Precio
            $table->integer('stock'); // Cantidad disponible
            $table->integer('min_stock')->default(0); // Stock mínimo para alertas
            $table->date('expiration_date')->nullable(); // Fecha de caducidad
            $table->enum('status', ['activo', 'descontinuado', 'reservado'])->default('activo'); // Estado del productoF
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
