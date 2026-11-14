<?php

namespace App\Livewire;

use App\Enums\SessionStatus;
use App\Enums\UserRole;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizSession;
use App\Models\SessionParticipant;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class QuizSessionHost extends Component
{
    public Quiz $quiz;

    public ?QuizSession $session = null;

    public function mount(Quiz $quiz): void
    {
        $user = Auth::user();

        if ($user === null || ($quiz->created_by !== Auth::id() && ! $user->isAdmin())) {
            redirect()->to(route('dashboard'))->with('error', 'You do not have permission to host this quiz.');

            return;
        }

        $this->quiz = $quiz;

        if ($quiz->questions()->count() === 0) {
            redirect()->to(route('dashboard'))->with('error', 'This quiz has no questions. Add questions before hosting.');

            return;
        }

        $existing = QuizSession::query()->where('quiz_id', $quiz->id)
            ->where('host_id', Auth::id())
            ->whereIn('status', [SessionStatus::Waiting->value, SessionStatus::Active->value])
            ->first();

        if ($existing) {
            $this->session = $existing;

            return;
        }

        $session = QuizSession::query()->create([
            'quiz_id' => $quiz->id,
            'host_id' => Auth::id(),
            'token'   => (string) Str::uuid(),
            'status'  => SessionStatus::Waiting->value,
        ]);

        SessionParticipant::query()->updateOrCreate(
            ['session_id' => $session->id, 'user_id' => Auth::id()],
            [
                'role'      => UserRole::Host,
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

    public function currentQuestion(): ?Question
    {
        if (! $this->session) {
            return null;
        }

        $questions = $this->session->loadMissing('quiz.questions.answers')->quiz->questions;

        return $questions->get($this->session->current_question_index) ?: null;
    }

    public function participantCount(): int
    {
        if (! $this->session) {
            return 0;
        }

        $this->session->loadMissing('participants');

        return (int) $this->session->participants
            ->where('role', '!=', UserRole::Host)
            ->count();
    }

    public function inviteLink(): string
    {
        if (! $this->session) {
            return '';
        }

        return url('/session/' . $this->session->token . '/join');
    }

    public function totalQuestions(): int
    {
        return max($this->quiz->questions()->count(), 1);
    }

    public function startSession(): void
    {
        if (! $this->session) {
            return;
        }

        $this->session->update([
            'status' => SessionStatus::Active->value,
        ]);
    }

    public function showQuestion(): void
    {
        if (! $this->session) {
            return;
        }

        $this->session->update([
            'show_answer' => false,
            'show_scores' => false,
        ]);
    }

    public function revealAnswer(): void
    {
        if (! $this->session) {
            return;
        }

        $this->session->update([
            'show_answer' => true,
        ]);
    }

    public function showScores(): void
    {
        if (! $this->session) {
            return;
        }

        $this->session->update([
            'show_scores' => true,
        ]);
    }

    public function nextQuestion(): void
    {
        if (! $this->session) {
            return;
        }

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
        if (! $this->session) {
            return;
        }

        $prevIndex = max($this->session->current_question_index - 1, 0);

        $this->session->update([
            'current_question_index' => $prevIndex,
            'show_answer'            => false,
            'show_scores'            => false,
        ]);
    }

    public function endSession(): void
    {
        if (! $this->session) {
            return;
        }

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

    public function render(): View
    {
        return view('livewire.quiz-session-host')->layout('components.layouts.sidebar', ['title' => 'Host Session']);
    }
}
