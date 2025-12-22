<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;

class ProductIdempotencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_idempotency_prevents_duplicate_creation()
    {
        $cat = Category::factory()->create();
        $payload = [
            'category_id' => $cat->id,
            'sku_code' => 'IDEMP-001',
            'name' => 'Idempotent product',
            'price_base' => 10,
            'stock_available' => 2,
            'idempotency_key' => 'unique-key-123'
        ];

        $res1 = $this->postJson('/products', $payload);
        $res1->assertStatus(201);

        $res2 = $this->postJson('/products', $payload);
        $res2->assertStatus(409);
    }
}
