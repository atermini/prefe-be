<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'prompt' => fake()->sentence(8),
            'option_a' => fake()->sentence(3),
            'option_b' => fake()->sentence(3),
            'active_on' => fake()->unique()->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
        ];
    }
}
