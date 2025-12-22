<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Crear categorías iniciales
     */
    public function run(): void
    {
        $categories = [
            'Laptops',
            'Teléfonos',
            'Tablets',
            'Accesorios',
            'Monitores',
            'Teclados',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name)]
            );
        }
        
        $this->command->info('✅ Categorías creadas: ' . count($categories));
    }
}
