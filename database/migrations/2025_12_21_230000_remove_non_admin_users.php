<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Eliminar todos los usuarios excepto el administrador principal.
     * El sistema solo debe tener un único usuario administrador.
     */
    public function up(): void
    {
        // Obtener el admin principal (el que tiene is_admin = true, o el id=1)
        $admin = DB::table('users')
            ->where('is_admin', true)
            ->orWhere('id', 1)
            ->orderBy('is_admin', 'desc')
            ->orderBy('id', 'asc')
            ->first();

        if ($admin) {
            // Eliminar todos los usuarios excepto el admin
            DB::table('users')
                ->where('id', '!=', $admin->id)
                ->delete();

            // Asegurarse de que el admin tenga is_admin = true
            DB::table('users')
                ->where('id', $admin->id)
                ->update(['is_admin' => true]);
        }
    }

    /**
     * No se puede revertir esta migración (los usuarios eliminados se pierden).
     */
    public function down(): void
    {
        // No es posible restaurar usuarios eliminados
    }
};
