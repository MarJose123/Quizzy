<?php

namespace App\Models;

use Database\Factories\QuizFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property int         $created_by
 * @property string      $title
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $creator
 * @property-read Collection<int, Question> $questions
 * @property-read Collection<int, QuizSession> $sessions
 */
#[Fillable(['created_by', 'title', 'description'])]
class Quiz extends Model
{
    /** @use HasFactory<QuizFactory> */
    use HasFactory;

    /**
     * Get the user who created the quiz.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the questions for the quiz.
     *
     * @return HasMany<Question, $this>
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    /**
     * Get the sessions for the quiz.
     *
     * @return HasMany<QuizSession, $this>
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(QuizSession::class);
    }
}
