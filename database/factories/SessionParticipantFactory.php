<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\QuizSession;
use App\Models\SessionParticipant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SessionParticipant>
 */
class SessionParticipantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'session_id' => QuizSession::factory(),
            'user_id'    => User::factory(),
            'role'       => UserRole::Participant,
            'joined_at'  => now(),
        ];
    }

    /**
     * Indicate that the participant is a host.
     */
    public function host(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::Host,
        ]);
    }

    /**
     * Indicate that the participant is a viewer.
     */
    public function viewer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::Viewer,
        ]);
    }
}
