<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: Tabla de productos favoritos
 * 
 * Permite a los usuarios cliente guardar productos como favoritos
 * para acceso rápido y seguimiento de precios/disponibilidad.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable(); // Notas personales del cliente
            $table->timestamps();
            
            // Índices para performance
            $table->unique(['user_id', 'product_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
