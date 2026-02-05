<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph,
            'type' => fake()->randomElement(['perdu', 'trouve']),
            'location' => fake()->address,
            'date' => fake()->date(),
            'status' => fake()->randomElement(['en attente', 'retourné', 'non retourné']),
            'user_id' => fake()->numberBetween(1, 10),
        ];
    }
}
