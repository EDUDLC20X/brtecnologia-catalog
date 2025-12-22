<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = $this->faker->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'sku_code' => strtoupper(Str::random(8)),
            'name' => $name,
            'description' => $this->faker->optional()->paragraph(),
            'technical_specs' => [
                'color' => $this->faker->safeColorName(),
                'weight' => $this->faker->randomFloat(2, 0.1, 5) . 'kg',
            ],
            'stock_available' => $this->faker->numberBetween(0, 100),
            'price_base' => $this->faker->randomFloat(2, 1, 1000),
            'is_active' => true,
        ];
    }
}
