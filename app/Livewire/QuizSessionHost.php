<?php

namespace App\Livewire;

use App\Enums\SessionStatus;
use App\Enums\UserRole;
use App\Models\Quiz;
use App\Models\QuizSession;
use App\Models\SessionParticipant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class QuizSessionHost extends Component
{
    public Quiz $quiz;

    public ?QuizSession $session = null;

    public function mount(Quiz $quiz): void
    {
        $this->quiz = $quiz;

        if ($quiz->questions()->count() === 0) {
            redirect()->to(route('dashboard'))->with('error', 'This quiz has no questions. Add questions before hosting.');

            return;
        }

        $existing = QuizSession::where('quiz_id', $quiz->id)
            ->where('host_id', Auth::id())
            ->whereIn('status', [SessionStatus::Waiting->value, SessionStatus::Active->value])
            ->first();

        if ($existing) {
            $this->session = $existing;

            return;
        }

        $session = QuizSession::create([
            'quiz_id' => $quiz->id,
            'host_id' => Auth::id(),
            'token'   => (string) Str::uuid(),
            'status'  => SessionStatus::Waiting->value,
        ]);

        SessionParticipant::firstOrCreate(
            ['session_id' => $session->id, 'user_id' => Auth::id()],
            [
                'role'      => UserRole::Host->value,
                'joined_at' => now(),
            ]
        );

        $this->session = $session;
    }

    public function poll(): void
    {
        if ($this->session) {
            $this->session->refresh();
        }
    }

    public function currentQuestion()
    {
        if (! $this->session) {
            return;
        }

        $questions = $this->session->quiz->questions;

        return $questions->get($this->session->current_question_index) ?: null;
    }

    public function participantCount()
    {
        if (! $this->session) {
            return 0;
        }

        return $this->session->participants()
            ->where('role', '!=', UserRole::Host->value)
            ->count();
    }

    public function inviteLink()
    {
        if (! $this->session) {
            return '';
        }

        return url('/session/' . $this->session->token . '/join');
    }

    public function totalQuestions()
    {
        return max($this->quiz->questions()->count(), 1);
    }

    public function startSession(): void
    {
        $this->session->update([
            'status' => SessionStatus::Active->value,
        ]);
    }

    public function showQuestion(): void
    {
        $this->session->update([
            'show_answer' => false,
            'show_scores' => false,
        ]);
    }

    public function revealAnswer(): void
    {
        $this->session->update([
            'show_answer' => true,
        ]);
    }

    public function showScores(): void
    {
        $this->session->update([
            'show_scores' => true,
        ]);
    }

    public function nextQuestion(): void
    {
        $maxIndex = $this->totalQuestions() - 1;
        $nextIndex = min($this->session->current_question_index + 1, $maxIndex);

        $this->session->update([
            'current_question_index' => $nextIndex,
            'show_answer'            => false,
            'show_scores'            => false,
        ]);
    }

    public function previousQuestion(): void
    {
        $prevIndex = max($this->session->current_question_index - 1, 0);

        $this->session->update([
            'current_question_index' => $prevIndex,
            'show_answer'            => false,
            'show_scores'            => false,
        ]);
    }

    public function endSession(): void
    {
        $this->session->update([
            'status'  => SessionStatus::Finished->value,
            'ends_at' => now(),
        ]);

        redirect()->to(route('dashboard'));
    }

    public function copyInviteLink(): void
    {
        $link = $this->inviteLink();
        $this->dispatch('invite-link-copied', link: $link);
    }

    public function render()
    {
        return view('livewire.quiz-session-host');
    }
}
