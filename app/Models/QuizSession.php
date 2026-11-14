<?php

namespace App\Models;

use App\Enums\SessionStatus;
use Database\Factories\QuizSessionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int           $id
 * @property int           $quiz_id
 * @property int           $host_id
 * @property string        $token
 * @property int           $current_question_index
 * @property SessionStatus $status
 * @property bool          $show_answer
 * @property bool          $show_scores
 * @property Carbon|null   $ends_at
 * @property Carbon|null   $created_at
 * @property Carbon|null   $updated_at
 * @property-read Quiz $quiz
 * @property-read User $host
 * @property-read Collection<int, SessionParticipant> $participants
 * @property-read Collection<int, SessionAnswer> $answers
 */
#[Fillable(['quiz_id', 'host_id', 'token', 'current_question_index', 'status', 'show_answer', 'show_scores', 'ends_at'])]
class QuizSession extends Model
{
    /** @use HasFactory<QuizSessionFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status'      => SessionStatus::class,
            'show_answer' => 'boolean',
            'show_scores' => 'boolean',
            'ends_at'     => 'datetime',
        ];
    }

    /**
     * Get the quiz for the session.
     *
     * @return BelongsTo<Quiz, $this>
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the host user.
     *
     * @return BelongsTo<User, $this>
     */
    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    /**
     * Get the participants in the session.
     *
     * @return HasMany<SessionParticipant, $this>
     */
    public function participants(): HasMany
    {
        return $this->hasMany(SessionParticipant::class, 'session_id');
    }

    /**
     * Get the answers submitted in the session.
     *
     * @return HasMany<SessionAnswer, $this>
     */
    public function answers(): HasMany
    {
        return $this->hasMany(SessionAnswer::class, 'session_id');
    }

    /**
     * Get the current question being displayed.
     */
    public function currentQuestion(): ?Question
    {
        $this->loadMissing('quiz.questions');

        $questions = $this->quiz->questions;

        return $questions->get($this->current_question_index) ?: null;
    }

    /**
     * Check if the session is active.
     */
    public function isActive(): bool
    {
        return $this->status === SessionStatus::Active;
    }

    /**
     * Check if the session has finished.
     */
    public function isFinished(): bool
    {
        return $this->status === SessionStatus::Finished;
    }
}
