<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Crea el usuario administrador para el sistema de catálogo B&R Tecnología
     *
     * @return void
     */
    public function run()
    {
        // Solo crear si no existe un admin
        if (!User::where('is_admin', true)->exists()) {
            User::create([
                'name' => 'Administrador B&R',
                'email' => 'lcruz276_est@instipp.edu.ec',
                'email_verified_at' => now(),
                'password' => Hash::make('Admin2025BR'),
                'is_admin' => true,
            ]);
            
            $this->command->info('✅ Usuario administrador creado exitosamente');
            $this->command->info('   Email: lcruz276_est@instipp.edu.ec');
            $this->command->info('   Contraseña: Admin2025BR');
        } else {
            $this->command->info('ℹ️ Ya existe un usuario administrador');
        }
    }
}
