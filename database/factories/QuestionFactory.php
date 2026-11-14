<?php

namespace Database\Factories;

use App\Enums\QuestionType;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Question>
 */
class QuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quiz_id' => Quiz::factory(),
            'text'    => fake()->sentence(),
            'type'    => fake()->randomElement([QuestionType::MultipleChoice, QuestionType::TrueFalse]),
            'points'  => 10,
            'order'   => 0,
        ];
    }
}
