<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuizAnswer>
 */
class AnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake()->text(),
            'sort' => fake()->numberBetween(1,4),
            'is_correct' => fake()->boolean(),
            'image_path' => fake()->randomElement([null, '/tmp/my-image124.jpg']),
        ];
    }
}
