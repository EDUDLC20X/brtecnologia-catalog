<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Make user_id nullable so guest checkouts are allowed
        DB::statement('ALTER TABLE orders ALTER COLUMN user_id DROP NOT NULL');
    }

    public function down(): void
    {
        // Restore NOT NULL constraint; note: this will fail if null values exist
        DB::statement('ALTER TABLE orders ALTER COLUMN user_id SET NOT NULL');
    }
};
