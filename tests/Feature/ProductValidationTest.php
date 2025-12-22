<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;

class ProductValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_price_must_be_positive_and_stock_non_negative()
    {
        $cat = Category::factory()->create();

        $response = $this->post('/products', [
            'category_id' => $cat->id,
            'sku_code' => 'TST-001',
            'name' => 'Test Product',
            'price_base' => -5,
            'stock_available' => -1,
        ]);

        $response->assertSessionHasErrors(['price_base','stock_available']);
    }

    public function test_name_unique_per_category()
    {
        $cat = Category::factory()->create();
        // create existing product
        \App\Models\Product::factory()->create(['category_id' => $cat->id, 'name' => 'UniqueName']);

        $response = $this->post('/products', [
            'category_id' => $cat->id,
            'sku_code' => 'TST-002',
            'name' => 'UniqueName',
            'price_base' => 10,
            'stock_available' => 1,
        ]);

        $response->assertSessionHasErrors(['name']);
    }
}
