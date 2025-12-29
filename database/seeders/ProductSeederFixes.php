<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeederFixes extends Seeder
{
    /**
     * Arreglar productos con códigos duplicados - usar códigos alternativos
     */
    public function run(): void
    {
        $categoryIds = Category::pluck('id', 'name')->toArray();

        // Productos que tenían conflictos de códigos - usar códigos alternativos con prefijo ALT
        $products = [
            // COD 9703 duplicado - POWER STATION BLUETTI (el Alcohol Isopropílico ya existe)
            ['cod' => 'ALT9703', 'name' => 'POWER STATION BLUETTI AC70 1000W 768WH LiFePO4 PORTATIL', 'category' => 'Power Station'],
            
            // COD 10303 duplicado - POWER STATION ECOFLOW DELTA 2 (el CPU Desktop ya existe)
            ['cod' => 'ALT10303', 'name' => 'POWER STATION ECOFLOW DELTA 2 1024Wh AC: 1,800W/X-BOOST: 2,700W LiFePO4 PORTATIL', 'category' => 'Power Station'],
            
            // COD 3107 duplicado - TINTA EPSON 664 BLACK (el Disco Duro ya existe)
            ['cod' => 'ALT3107', 'name' => 'TINTA EPSON BOTELLA 664 T6641 BLACK ECOTANK 70ML L220 L380 L375 L395 L396 L4150 L575 L555', 'category' => 'Tintas y Tóners'],
            
            // COD 6042 duplicado - TINTA EPSON 673 Light Magenta (la Maleta ya existe)
            ['cod' => 'ALT6042', 'name' => 'TINTA EPSON BOTELLA 673 T673620 LIGHT MAGENTA ECOTANK 70ML L800 L810 L1800 L805 L850', 'category' => 'Tintas y Tóners'],
            
            // COD 4524 duplicado - VENTILADOR COOLER MASTER (la Impresora ya existe)
            ['cod' => 'ALT4524', 'name' => 'VENTILADOR PARA LAPTOP COOLER MASTER NOTEPAL X-SLIM II 1 FAN', 'category' => 'Ventiladores PC'],
            
            // COD 9707 duplicado - POWER BANK ANKER (la Memoria RAM ya existe)
            ['cod' => 'ALT9707', 'name' => 'POWER BANK ANKER 313 A1109 POWERCORE 10000MAH 12W USBA BLACK', 'category' => 'Power Bank'],
            
            // COD 5719 duplicado - TINTA HP GT53 BLACK (la Memoria RAM ya existe)
            ['cod' => 'ALT5719', 'name' => 'TINTA HP BOTELLA GT53 ORIGINAL BLACK 1VV22AL 90ML DeskJet GT5820/5810/315/415/115/InkTank', 'category' => 'Tintas y Tóners'],
        ];

        $created = 0;
        $skipped = 0;
        
        foreach ($products as $product) {
            $categoryId = $categoryIds[$product['category']] ?? null;
            if (!$categoryId) {
                $this->command->warn("Categoría no encontrada: {$product['category']}");
                continue;
            }

            // Verificar si ya existe con el código alternativo
            if (Product::where('sku_code', $product['cod'])->exists()) {
                $skipped++;
                continue;
            }

            Product::create([
                'sku_code' => $product['cod'],
                'name' => $product['name'],
                'slug' => Str::slug($product['name'] . '-' . $product['cod']),
                'description' => $product['name'],
                'technical_specs' => json_encode(['COD_ORIGINAL' => str_replace('ALT', '', $product['cod']), 'COD_SISTEMA' => $product['cod']]),
                'category_id' => $categoryId,
                'stock_available' => rand(5, 50),
                'price_base' => rand(50, 800) + (rand(0, 99) / 100),
                'is_active' => true,
                'is_featured' => rand(0, 10) > 8,
            ]);
            $created++;
        }
        
        if ($skipped > 0) {
            $this->command->info("⏭️ Productos saltados (ya existían): {$skipped}");
        }
        
        $this->command->info("✅ Productos con códigos arreglados: {$created}");
        
        // Resumen final
        $totalProducts = Product::count();
        $totalCategories = Category::has('products')->count();
        $this->command->info("========================================");
        $this->command->info("📊 RESUMEN TOTAL DEL INVENTARIO B&R:");
        $this->command->info("   Total de Productos: {$totalProducts}");
        $this->command->info("   Total de Categorías con productos: {$totalCategories}");
        $this->command->info("========================================");
    }
}
