<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: Tabla de historial de productos visualizados
 * 
 * Registra los productos que cada usuario ha visualizado para:
 * - Mostrar historial de navegación
 * - Generar recomendaciones personalizadas
 * - Permitir retomar navegación donde se dejó
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('view_count')->default(1); // Veces que ha visto el producto
            $table->timestamp('last_viewed_at');
            $table->timestamps();
            
            // Índices para performance y consultas frecuentes
            $table->unique(['user_id', 'product_id']);
            $table->index(['user_id', 'last_viewed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_views');
    }
};
