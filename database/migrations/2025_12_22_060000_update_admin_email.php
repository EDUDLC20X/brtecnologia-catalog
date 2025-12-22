<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update admin email directly
        DB::table('users')
            ->where('email', 'lcruz276_est@instipp.edu.ec')
            ->update([
                'email' => 'eduardodlcruz05@gmail.com',
                'email_verified_at' => now(), // Mark as verified
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')
            ->where('email', 'eduardodlcruz05@gmail.com')
            ->update([
                'email' => 'lcruz276_est@instipp.edu.ec',
                'email_verified_at' => now(),
            ]);
    }
};
