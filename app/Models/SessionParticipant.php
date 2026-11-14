<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\SessionParticipantFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property int         $session_id
 * @property int         $user_id
 * @property UserRole    $role
 * @property Carbon      $joined_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read QuizSession $session
 * @property-read User $user
 * @property-read Collection<int, SessionAnswer> $answers
 */
#[Fillable(['session_id', 'user_id', 'role', 'joined_at'])]
class SessionParticipant extends Model
{
    /** @use HasFactory<SessionParticipantFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role'      => UserRole::class,
            'joined_at' => 'datetime',
        ];
    }

    /**
     * Get the session for the participant.
     *
     * @return BelongsTo<QuizSession, $this>
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(QuizSession::class);
    }

    /**
     * Get the user for the participant.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the answers submitted by the participant.
     *
     * @return HasMany<SessionAnswer, $this>
     */
    public function answers(): HasMany
    {
        return $this->hasMany(SessionAnswer::class, 'participant_id');
    }

    /**
     * Get the participant's score based on points of correct answers.
     */
    public function score(): int
    {
        if (! $this->relationLoaded('answers')) {
            $this->load('answers.question');
        }

        return (int) $this->answers->where('is_correct', true)->sum(fn (SessionAnswer $answer) => $answer->question->points ?? 10);
    }

    /**
     * Check if the participant is a host.
     */
    public function isHost(): bool
    {
        return $this->role === UserRole::Host;
    }

    /**
     * Check if the participant is a viewer.
     */
    public function isViewer(): bool
    {
        return $this->role === UserRole::Viewer;
    }
}
