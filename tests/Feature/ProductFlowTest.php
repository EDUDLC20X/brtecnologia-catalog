<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;

class ProductFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_product_flow()
    {
        $this->actingAs(\App\Models\User::factory()->create());
        $cat = Category::factory()->create();

        // create
        $res = $this->post('/products', [
            'category_id' => $cat->id,
            'sku_code' => 'FLOW-001',
            'name' => 'Flow Product',
            'price_base' => 20,
            'stock_available' => 5,
        ]);
        $res->assertRedirect();

        $product = Product::where('sku_code','FLOW-001')->first();
        $this->assertNotNull($product);

        // edit
        $res2 = $this->patch('/products/'.$product->id, [
            'category_id' => $cat->id,
            'sku_code' => 'FLOW-001',
            'name' => 'Flow Product Updated',
            'price_base' => 25,
            'stock_available' => 3,
        ]);
        $res2->assertRedirect();

        $product->refresh();
        $this->assertEquals('Flow Product Updated', $product->name);

        // list
        $res3 = $this->get('/products');
        $res3->assertStatus(200);

        // delete
        $res4 = $this->delete('/products/'.$product->id);
        $res4->assertRedirect();

        $this->assertNull(Product::find($product->id));
    }
}
