<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_contents', function (Blueprint $table) {
            $table->string('cloudinary_public_id')->nullable()->after('image_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_contents', function (Blueprint $table) {
            $table->dropColumn('cloudinary_public_id');
        });
    }
};
