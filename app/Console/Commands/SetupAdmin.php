<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Category;

class SetupAdmin extends Command
{
    protected $signature = 'setup:admin';
    protected $description = 'Create admin user and default categories';

    public function handle()
    {
        // Crear/asegurar usuario admin según ADMIN_EMAILS (primera entrada)
        // o usar el admin existente con is_admin = true
        $adminList = config('mail.admin_address', env('ADMIN_EMAILS', ''));
        $emails = array_filter(array_map('trim', explode(',', $adminList)));
        $primary = $emails[0] ?? 'admin@example.com';

        $admin = User::firstOrCreate(
            ['email' => $primary],
            [
                'name' => 'Administrador',
                'password' => bcrypt(\Illuminate\Support\Str::random(24)),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );

        // Asegurarse de que el admin tenga el rol is_admin
        if (!$admin->is_admin) {
            $admin->update(['is_admin' => true]);
        }

        $this->info("Admin user: {$admin->email} (ID: {$admin->id}, is_admin: true)");

        // Remove legacy placeholders and id=1 if they are not the chosen primary admin
        $placeholders = ['admin@example.com', 'admin@localhost', 'admin@ecommerce.com'];
        foreach ($placeholders as $ph) {
            if (strtolower($ph) === strtolower($primary)) continue;
            User::where('email', $ph)->delete();
        }

        $user1 = User::find(1);
        if ($user1 && strtolower($user1->email) !== strtolower($primary)) {
            $user1->delete();
        }

        // Contar categorías
        $categoryCount = Category::count();
        $this->info("Categorías existentes: {$categoryCount}");

        if ($categoryCount === 0) {
            $this->info("Creando categorías...");
            $categories = [
                ['name' => 'Electrónica', 'description' => 'Productos electrónicos variados'],
                ['name' => 'Ropa', 'description' => 'Prendas de vestir'],
                ['name' => 'Hogar', 'description' => 'Artículos para el hogar'],
                ['name' => 'Libros', 'description' => 'Libros y material de lectura'],
                ['name' => 'Deportes', 'description' => 'Artículos deportivos'],
            ];
            foreach ($categories as $cat) {
                Category::firstOrCreate($cat);
                $this->line("✓ Categoría: {$cat['name']}");
            }
        }

        $this->info("Done!");
    }
}
