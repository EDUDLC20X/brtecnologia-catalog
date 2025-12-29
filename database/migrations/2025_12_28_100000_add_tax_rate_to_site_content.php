<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SiteContent;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Agregar configuración de IVA al sistema
        SiteContent::firstOrCreate(
            ['key' => 'global.tax_rate'],
            [
                'section' => 'global',
                'label' => 'Tasa de IVA (%)',
                'type' => 'text',
                'value' => '12',
                'default_value' => '12',
                'help_text' => 'Porcentaje de IVA aplicado a las cotizaciones (ej: 12 para 12%)',
                'order' => 99,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        SiteContent::where('key', 'global.tax_rate')->delete();
    }
};
