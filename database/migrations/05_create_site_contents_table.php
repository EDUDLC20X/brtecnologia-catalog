<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: Tabla de contenido del sitio (CMS)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section');
            $table->string('key');
            $table->string('label')->nullable();
            $table->string('type')->default('text');
            $table->text('value')->nullable();
            $table->text('default_value')->nullable();
            $table->string('image_path')->nullable();
            $table->string('cloudinary_public_id')->nullable();
            $table->text('help_text')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->unique(['section', 'key']);
            $table->index('section');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_contents');
    }
};
