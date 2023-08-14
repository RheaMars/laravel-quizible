<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuizQuestion>
 */
class
QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quiz_id' => fake()->numberBetween(1,30),
            'type' => fake()->randomElement(['multiple-choice', 'true-false', 'short-answer']),
            'content' => fake()->sentence(),
            'points' => fake()->randomElement([null, fake()->numberBetween(1, 5)]),
            'explanation' => fake()->randomElement([null, fake()->sentence()]),
            'image_path' => fake()->randomElement([null, '/tmp/my-image124.jpg']),
            'sort' => fake()->numberBetween(1,4),
        ];
    }
}
