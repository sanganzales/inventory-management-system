<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'productId'=>$this->faker->unique()->numberBetween($min=6,$max=42),
            'quantity'=>$this->faker->numberBetween($min=13,$max=40),
            'warehouseId'=>1,
            'createdBy'=>1,
        ];
    }
}
