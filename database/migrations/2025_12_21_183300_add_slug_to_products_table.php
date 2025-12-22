<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero agregar columnas sin restricción unique
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->default('temp-slug')->after('name');
            $table->integer('views_count')->default(0)->after('is_active');
            $table->boolean('is_featured')->default(false)->after('is_active');
        });

        // Generar slugs para productos existentes
        $products = \DB::table('products')->get();
        foreach ($products as $product) {
            $slug = Str::slug($product->name ?: 'product-' . $product->id);
            if (empty($slug)) {
                $slug = 'product-' . $product->id;
            }
            // Asegurar unicidad
            $originalSlug = $slug;
            $count = 1;
            while (\DB::table('products')->where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            \DB::table('products')->where('id', $product->id)->update(['slug' => $slug]);
        }

        // Ahora agregar el índice único
        Schema::table('products', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'views_count', 'is_featured']);
        });
    }
};
