<?php

namespace App\Models;

use Database\Factories\SessionAnswerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property int         $session_id
 * @property int         $participant_id
 * @property int         $question_id
 * @property int         $answer_id
 * @property bool        $is_correct
 * @property Carbon      $submitted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read QuizSession $session
 * @property-read SessionParticipant $participant
 * @property-read Question $question
 * @property-read Answer $answer
 */
#[Fillable(['session_id', 'participant_id', 'question_id', 'answer_id', 'is_correct', 'submitted_at'])]
class SessionAnswer extends Model
{
    /** @use HasFactory<SessionAnswerFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_correct'   => 'boolean',
            'submitted_at' => 'datetime',
        ];
    }

    /**
     * Get the session for the answer.
     *
     * @return BelongsTo<QuizSession, $this>
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(QuizSession::class);
    }

    /**
     * Get the participant who submitted the answer.
     *
     * @return BelongsTo<SessionParticipant, $this>
     */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(SessionParticipant::class, 'participant_id');
    }

    /**
     * Get the question for the answer.
     *
     * @return BelongsTo<Question, $this>
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the selected answer.
     *
     * @return BelongsTo<Answer, $this>
     */
    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }
}
