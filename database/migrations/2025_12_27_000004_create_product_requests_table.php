<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: Tabla de solicitudes de productos
 * 
 * Almacena las solicitudes de información/cotización de productos
 * realizadas por usuarios (autenticados o visitantes).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Datos del solicitante (si no está autenticado)
            $table->string('name');
            $table->string('email');
            $table->string('phone', 20)->nullable();
            $table->string('company')->nullable();
            
            // Detalles de la solicitud
            $table->integer('quantity')->default(1);
            $table->text('message')->nullable();
            
            // Estado de la solicitud
            $table->enum('status', ['pending', 'contacted', 'quoted', 'completed', 'cancelled'])
                  ->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('responded_at')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_requests');
    }
};
