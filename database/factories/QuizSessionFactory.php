<?php

namespace Database\Factories;

use App\Enums\SessionStatus;
use App\Models\Quiz;
use App\Models\QuizSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<QuizSession>
 */
class QuizSessionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quiz_id'                => Quiz::factory(),
            'host_id'                => User::factory(),
            'token'                  => fn () => (string) Str::uuid(),
            'current_question_index' => 0,
            'status'                 => SessionStatus::Waiting,
            'show_answer'            => false,
            'show_scores'            => false,
            'ends_at'                => null,
        ];
    }

    /**
     * Indicate that the session is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => SessionStatus::Active,
        ]);
    }

    /**
     * Indicate that the session is finished.
     */
    public function finished(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'  => SessionStatus::Finished,
            'ends_at' => now(),
        ]);
    }
}
