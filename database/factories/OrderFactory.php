<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'total' => fake()->randomFloat($nbMaxDecimals = 2, $min = 1000, $max = 9999),
            'address' => fake()->address(),
            'phone' =>  fake()->phoneNumber(),
        ];
    }
}
