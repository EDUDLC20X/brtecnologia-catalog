<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
            \App\Models\Category::firstOrCreate(['name' => $name]);
        }
    }
}
