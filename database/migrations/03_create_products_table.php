<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: Tabla de productos
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sku_code')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('technical_specs')->nullable();
            $table->integer('stock_available')->default(0);
            $table->decimal('price_base', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            
            // Campos de ofertas
            $table->boolean('is_on_sale')->default(false);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->timestamp('sale_starts_at')->nullable();
            $table->timestamp('sale_ends_at')->nullable();
            
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index('category_id');
            $table->index('is_active');
            $table->index('price_base');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
