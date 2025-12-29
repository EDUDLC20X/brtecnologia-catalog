<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class UpdateCategoryIconsSeeder extends Seeder
{
    /**
     * Run the database seeds para actualizar íconos de categorías existentes.
     */
    public function run(): void
    {
        Log::info('UpdateCategoryIconsSeeder: Iniciando actualización de íconos de categorías');

        $categoryIconMap = [
            // Mapeo semántico de nombres de categoría a íconos Bootstrap Icons
            'computadoras' => 'bi-laptop',
            'laptops' => 'bi-laptop',
            'portátiles' => 'bi-laptop',
            'móviles' => 'bi-phone',
            'celulares' => 'bi-phone',
            'smartphones' => 'bi-phone',
            'teléfonos' => 'bi-phone',
            'tablets' => 'bi-tablet',
            'tabletas' => 'bi-tablet',
            'impresoras' => 'bi-printer',
            'periféricos' => 'bi-keyboard',
            'teclados' => 'bi-keyboard',
            'mouse' => 'bi-mouse',
            'ratones' => 'bi-mouse',
            'audio' => 'bi-headphones',
            'audífonos' => 'bi-headphones',
            'auriculares' => 'bi-headphones',
            'cámaras' => 'bi-camera',
            'fotografía' => 'bi-camera',
            'pantallas' => 'bi-tv',
            'monitores' => 'bi-display',
            'televisores' => 'bi-tv',
            'redes' => 'bi-router',
            'networking' => 'bi-router',
            'routers' => 'bi-router',
            'almacenamiento' => 'bi-hdd',
            'discos' => 'bi-hdd',
            'usb' => 'bi-usb-drive',
            'pendrives' => 'bi-usb-drive',
            'cables' => 'bi-ethernet',
            'conectores' => 'bi-plug',
            'componentes' => 'bi-cpu',
            'tarjetas gráficas' => 'bi-gpu-card',
            'gaming' => 'bi-controller',
            'videojuegos' => 'bi-controller',
            'bocinas' => 'bi-speaker',
            'altavoces' => 'bi-speaker',
            'micrófonos' => 'bi-mic',
            'webcams' => 'bi-webcam',
            'cámaras web' => 'bi-webcam',
            'accesorios' => 'bi-plug',
            'herramientas' => 'bi-tools',
            'seguridad' => 'bi-shield-lock',
            'software' => 'bi-code-square',
        ];

        $categories = Category::all();
        $updated = 0;

        foreach ($categories as $category) {
            // Si ya tiene ícono diferente de bi-box, no sobrescribir
            if (!empty($category->icon) && $category->icon !== 'bi-box') {
                Log::info("Categoría '{$category->name}' ya tiene ícono personalizado: {$category->icon}");
                continue;
            }

            // Buscar coincidencia en el mapeo
            $categoryNameLower = strtolower($category->name);
            $iconFound = null;

            foreach ($categoryIconMap as $keyword => $icon) {
                if (str_contains($categoryNameLower, $keyword)) {
                    $iconFound = $icon;
                    break;
                }
            }

            // Si no se encontró coincidencia, usar bi-box como default
            $newIcon = $iconFound ?? 'bi-box';

            // Actualizar categoría
            $category->icon = $newIcon;
            $category->save();

            Log::info("Categoría '{$category->name}' actualizada con ícono: {$newIcon}");
            $updated++;
        }

        Log::info("UpdateCategoryIconsSeeder: {$updated} categorías actualizadas con íconos semánticos");
        $this->command->info("✓ {$updated} categorías actualizadas con íconos semánticos");
    }
}
