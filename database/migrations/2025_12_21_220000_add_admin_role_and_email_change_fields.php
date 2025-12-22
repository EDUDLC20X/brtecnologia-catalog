<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Campo de rol para identificar administradores (no basado en email)
            $table->boolean('is_admin')->default(false)->after('email');
            
            // Campos para el proceso de cambio de correo con verificación
            $table->string('pending_email')->nullable()->after('is_admin');
            $table->string('email_change_token', 64)->nullable()->after('pending_email');
            $table->timestamp('email_change_requested_at')->nullable()->after('email_change_token');
        });

        // Marcar como admin al usuario con id=1 o el que tenga el email de ADMIN_EMAILS
        $adminList = config('mail.admin_address', env('ADMIN_EMAILS', ''));
        $adminEmails = array_filter(array_map('trim', explode(',', $adminList)));

        // Primero marcar por email configurado
        if (!empty($adminEmails)) {
            DB::table('users')
                ->whereIn('email', $adminEmails)
                ->update(['is_admin' => true]);
        }

        // También marcar al usuario id=1 como admin si existe
        DB::table('users')
            ->where('id', 1)
            ->update(['is_admin' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_admin', 'pending_email', 'email_change_token', 'email_change_requested_at']);
        });
    }
};
