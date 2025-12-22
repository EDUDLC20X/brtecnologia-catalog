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
        // Agregar logo para la barra de navegación
        $exists = DB::table('site_contents')->where('key', 'global.navbar_logo')->exists();
        
        if (!$exists) {
            DB::table('site_contents')->insert([
                'key' => 'global.navbar_logo',
                'section' => 'global',
                'label' => 'Logo Barra de Navegación',
                'type' => 'image',
                'value' => null,
                'default_value' => 'images/logo-br.png',
                'help_text' => 'Logo que aparece en la barra de navegación (junto a Dashboard). Recomendado: PNG con fondo transparente, tamaño máximo 220x55px',
                'order' => 3, // Entre logo principal y logo blanco
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Actualizar el orden de los logos existentes para mantener coherencia
            DB::table('site_contents')
                ->where('key', 'global.logo')
                ->update(['order' => 2, 'help_text' => 'Logo principal para el Hero y páginas públicas (PNG transparente recomendado)']);
            
            DB::table('site_contents')
                ->where('key', 'global.logo_white')
                ->update(['order' => 4, 'help_text' => 'Logo blanco para fondos oscuros (Hero, Login, Footer)']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('site_contents')->where('key', 'global.navbar_logo')->delete();
    }
};
