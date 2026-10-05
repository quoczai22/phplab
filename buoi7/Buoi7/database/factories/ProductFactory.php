<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->words(2, true),
            'price'=>$this->faker->numberBetween(10000,500000),
            'category_id'=> Category::factory(),
        ];
    }
}
