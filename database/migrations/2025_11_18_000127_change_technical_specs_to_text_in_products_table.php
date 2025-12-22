<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Convierte technical_specs a text en PostgreSQL
        DB::statement("ALTER TABLE products ALTER COLUMN technical_specs TYPE text USING technical_specs::text;");
    }

    public function down(): void
    {
        // Revertir a jsonb si fuera necesario
        DB::statement("ALTER TABLE products ALTER COLUMN technical_specs TYPE jsonb USING technical_specs::jsonb;");
    }
};