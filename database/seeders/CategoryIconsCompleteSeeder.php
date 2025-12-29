<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoryIconsCompleteSeeder extends Seeder
{
    /**
     * Actualizar íconos de todas las categorías de B&R Tecnología
     */
    public function run(): void
    {
        // Mapeo completo de categorías a íconos Bootstrap Icons
        $categoryIconMap = [
            'Adaptadores' => 'bi-plug',
            'AIO / Todo en Uno' => 'bi-display',
            'Herramientas y Limpieza' => 'bi-tools',
            'Audífonos y Headphones' => 'bi-headphones',
            'Baterías para Portátil' => 'bi-battery-charging',
            'Cartuchos y Cabezales' => 'bi-printer',
            'Cables de Video' => 'bi-hdmi-port',
            'Cables y Conectores' => 'bi-ethernet',
            'Cámaras de Vigilancia' => 'bi-camera-video',
            'Cargadores' => 'bi-lightning-charge',
            'Cases y Enclosures' => 'bi-hdd-rack',
            'Cases para PC' => 'bi-pc-display',
            'Cases Gamer' => 'bi-controller',
            'CPU Ensamblados' => 'bi-cpu',
            'Discos Duros' => 'bi-hdd',
            'Discos Sólidos SSD' => 'bi-device-ssd',
            'DVD Writer' => 'bi-disc',
            'Estuches y Mochilas' => 'bi-briefcase',
            'Electrodomésticos' => 'bi-house-gear',
            'Extensores WiFi' => 'bi-wifi',
            'Flash Memory' => 'bi-usb-drive',
            'Fuentes de Poder' => 'bi-lightning',
            'Hubs USB' => 'bi-usb-symbol',
            'Impresoras' => 'bi-printer',
            'Lectores de Código' => 'bi-upc-scan',
            'Licencias Software' => 'bi-key',
            'Mainboards' => 'bi-motherboard',
            'Memorias MicroSD' => 'bi-sd-card',
            'Memorias RAM' => 'bi-memory',
            'Mesas y Escritorios' => 'bi-table',
            'Monitores' => 'bi-display',
            'Mouse' => 'bi-mouse',
            'Pad Mouse' => 'bi-square',
            'Parlantes' => 'bi-speaker',
            'Pantallas para Portátil' => 'bi-laptop',
            'Papel y Suministros' => 'bi-file-earmark',
            'Procesadores' => 'bi-cpu-fill',
            'Proyectores' => 'bi-projector',
            'Protectores y Reguladores' => 'bi-shield-check',
            'Smart Watch' => 'bi-smartwatch',
            'Escáneres' => 'bi-printer',
            'Routers y Access Point' => 'bi-router',
            'Switches de Red' => 'bi-diagram-3',
            'Sillas' => 'bi-lamp',
            'Accesorios Tablet' => 'bi-tablet',
            'Tablets' => 'bi-tablet-landscape',
            'Adaptadores de Red' => 'bi-wifi',
            'Tarjetas de Red' => 'bi-pci-card',
            'Tarjetas de Video' => 'bi-gpu-card',
            'Teclados' => 'bi-keyboard',
            'Tintas y Tóners' => 'bi-droplet',
            'Tintas' => 'bi-droplet',
            'Televisores' => 'bi-tv',
            'TV Box' => 'bi-tv-fill',
            'Generadores' => 'bi-lightning-charge-fill',
            'Power Station' => 'bi-battery-full',
            'UPS' => 'bi-plug-fill',
            'Power Bank' => 'bi-battery-charging',
            'Ventiladores PC' => 'bi-fan',
            'Productos Amazon' => 'bi-alexa',
            'Laptops' => 'bi-laptop',
        ];

        $updated = 0;
        $notFound = [];

        foreach ($categoryIconMap as $name => $icon) {
            $category = Category::where('name', $name)->first();
            if ($category) {
                $category->icon = $icon;
                $category->save();
                $updated++;
                $this->command->info("✅ {$name} -> {$icon}");
            } else {
                $notFound[] = $name;
            }
        }

        // Actualizar categorías que no están en el mapeo con ícono por defecto
        $unmapped = Category::whereNull('icon')->orWhere('icon', '')->get();
        foreach ($unmapped as $category) {
            $category->icon = 'bi-box';
            $category->save();
            $this->command->warn("⚠️ {$category->name} -> bi-box (default)");
        }

        $this->command->info("========================================");
        $this->command->info("📦 Categorías actualizadas: {$updated}");
        if (count($notFound) > 0) {
            $this->command->warn("⚠️ No encontradas: " . implode(', ', $notFound));
        }
    }
}
