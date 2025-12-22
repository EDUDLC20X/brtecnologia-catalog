<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Agrega campos para manejar ofertas especiales en productos:
     * - is_on_sale: indica si el producto está en oferta
     * - sale_price: precio de oferta (descuento)
     * - sale_starts_at: fecha de inicio de la oferta
     * - sale_ends_at: fecha de fin de la oferta
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_on_sale')->default(false)->after('is_active');
            $table->decimal('sale_price', 10, 2)->nullable()->after('price_base');
            $table->timestamp('sale_starts_at')->nullable()->after('sale_price');
            $table->timestamp('sale_ends_at')->nullable()->after('sale_starts_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_on_sale', 'sale_price', 'sale_starts_at', 'sale_ends_at']);
        });
    }
};
