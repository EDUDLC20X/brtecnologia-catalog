<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: Agregar campos para usuarios cliente
 * 
 * Esta migración extiende la tabla users para soportar dos tipos de usuario:
 * - Administrador (is_admin = true): Gestión completa del sistema
 * - Cliente (is_admin = false): Favoritos, historial, personalización
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Teléfono para contacto del cliente
            $table->string('phone', 20)->nullable()->after('email');
            
            // Avatar/foto de perfil del cliente
            $table->string('avatar')->nullable()->after('phone');
            
            // Preferencias de notificaciones
            $table->boolean('notify_offers')->default(true)->after('is_admin');
            $table->boolean('notify_new_products')->default(true)->after('notify_offers');
            
            // Timestamp de última actividad (para recomendaciones)
            $table->timestamp('last_activity_at')->nullable()->after('notify_new_products');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'avatar',
                'notify_offers',
                'notify_new_products',
                'last_activity_at'
            ]);
        });
    }
};
