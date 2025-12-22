<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Read admin list from config (reads ADMIN_EMAILS)
        $adminList = config('mail.admin_address', env('ADMIN_EMAILS', ''));
        $emails = array_filter(array_map('trim', explode(',', $adminList)));

        if (empty($emails)) {
            // Nothing to do if ADMIN_EMAILS not set
            return;
        }

        $primary = $emails[0];

        // Ensure primary admin user exists
        $admin = User::where('email', $primary)->first();
        if (! $admin) {
            $admin = User::create([
                'name' => 'Administrador',
                'email' => $primary,
                'password' => bcrypt(\Illuminate\Support\Str::random(24)),
                'email_verified_at' => now(),
            ]);
        }

        // Remove known placeholder admin accounts
        $placeholders = ['admin@example.com', 'admin@localhost', 'admin@ecommerce.com'];
        foreach ($placeholders as $ph) {
            User::where('email', $ph)->where('email', '!=', $primary)->delete();
        }

        // If user with id=1 exists and is not the primary admin, remove it to avoid legacy admin access
        $user1 = User::find(1);
        if ($user1 && strtolower($user1->email) !== strtolower($primary)) {
            // Do not delete the primary admin if it happens to be id 1
            $user1->delete();
        }

        // Finally, remove any other users whose email exactly matches ADMIN_EMAILS entries except primary
        if (count($emails) > 1) {
            foreach ($emails as $mail) {
                $m = trim($mail);
                if ($m === $primary) continue;
                User::where('email', $m)->where('email', '!=', $primary)->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op (data destructive migration cannot be safely reversed)
    }
};
