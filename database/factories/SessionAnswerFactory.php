<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Question;
use App\Models\QuizSession;
use App\Models\SessionAnswer;
use App\Models\SessionParticipant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SessionAnswer>
 */
class SessionAnswerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'session_id'     => QuizSession::factory(),
            'participant_id' => SessionParticipant::factory(),
            'question_id'    => Question::factory(),
            'answer_id'      => Answer::factory(),
            'is_correct'     => false,
            'submitted_at'   => now(),
        ];
    }

    /**
     * Indicate that the answer is correct.
     */
    public function correct(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_correct' => true,
        ]);
    }
}
