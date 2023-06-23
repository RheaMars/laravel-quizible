<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuizAnswer>
 */
class QuizAnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question_id' => fake()->numberBetween(1,90),
            'content' => fake()->text(),
            'order_index' => fake()->numberBetween(1,4),
            'is_correct' => fake()->boolean(),
            'image_path' => fake()->randomElement([null, '/tmp/my-image124.jpg']),
        ];
    }
}
