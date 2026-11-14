<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Quiz>
 */
class QuizFactory extends Factory
{
    public function definition(): array
    {
        return [
            'created_by'  => User::factory(),
            'title'       => fake()->sentence(),
            'description' => fake()->optional()->paragraph(),
        ];
    }
}
