<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;

class ProductSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_summary_endpoint_returns_counts()
    {
        $cat = Category::factory()->create(['name'=>'Cat A']);
        Product::factory()->count(3)->create(['category_id' => $cat->id, 'stock_available' => 1]);
        Product::factory()->count(2)->create(['stock_available' => 10]);

        $this->actingAs(\App\Models\User::factory()->create());
        $res = $this->getJson('/products/summary');
        $res->assertStatus(200)->assertJsonStructure(['total','by_category','low_stock']);
    }
}
