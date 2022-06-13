<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
        'name' => $this->faker->unique()->word,
        'description'=>$this->faker->sentence,
        //'categoryId'=>$this->faker->numberBetween($min = 1, $max = 23),
        'createdBy'=>1,
        'brandId'=>$this->faker->numberBetween($min = 2003, $max = 2111),
        'barcode'=>null,
        'security_stock'=>10,
        'price'=>$this->faker->numberBetween($min = 20, $max = 100)*1000,
        ];
    }
}
