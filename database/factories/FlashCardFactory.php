<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FlashCard>
 */
class FlashCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'frontside' => fake()->sentence(),
            'backside' => fake()->text(),
            'category' => fake()->word(),
            'created_at' => fake()->dateTimeBetween('-1 year'),
        ];
    }
}
