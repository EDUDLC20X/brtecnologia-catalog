<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration de limpieza - Elimina tablas que no se usan en el catálogo
 * Este proyecto es solo un catálogo de productos, no un e-commerce completo.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Eliminar tablas que no se usan en este proyecto de catálogo
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('reviews');
        
        // Eliminar tablas de autenticación innecesarias (solo hay admin)
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('personal_access_tokens');
    }

    public function down(): void
    {
        // Si se necesitara revertir, las tablas se recrearían manualmente
        // Este proyecto no requiere estas funcionalidades
    }
};
