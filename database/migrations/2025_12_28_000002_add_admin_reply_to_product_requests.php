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
        Schema::table('product_requests', function (Blueprint $table) {
            // Cambiar el campo status para usar los nuevos estados
            // pendiente, en_proceso, respondido, cerrado
            
            // Agregar campo para respuesta del admin
            if (!Schema::hasColumn('product_requests', 'admin_reply')) {
                $table->text('admin_reply')->nullable()->after('admin_notes');
            }
            
            // Agregar campo para fecha de respuesta
            if (!Schema::hasColumn('product_requests', 'replied_at')) {
                $table->timestamp('replied_at')->nullable()->after('admin_reply');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_requests', function (Blueprint $table) {
            $table->dropColumn(['admin_reply', 'replied_at']);
        });
    }
};
