<?php

namespace App\Livewire;

use App\Enums\UserRole;
use App\Models\Answer;
use App\Models\QuizSession;
use App\Models\SessionAnswer;
use App\Models\SessionParticipant;
use App\Support\Avatars;
use Illuminate\Contracts\View\View;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizSessionParticipant extends Component
{
    public QuizSession $session;

    /** @var Collection<int, SessionParticipant> */
    public Collection $participants;

    public ?int $selectedAnswer = null;

    public bool $submitted = false;

    public function mount(string $token): void
    {
        $session = QuizSession::query()->where('token', $token)->with('quiz.questions.answers')->firstOrFail();
        $this->session = $session;

        $participant = SessionParticipant::query()->where('session_id', $session->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($participant === null) {
            redirect()->to(route('session.join', $token));

            return;
        }

        if ($participant->role !== UserRole::Participant) {
            redirect()->to(route('session.viewer', $token));

            return;
        }

        $this->participants = $session->participants()->with('user')->get();

        $currentQuestion = $session->currentQuestion();
        $this->submitted = $currentQuestion !== null && SessionAnswer::query()->where('session_id', $session->id)
            ->where('participant_id', $participant->id)
            ->where('question_id', $currentQuestion->id)
            ->exists();
    }

    public function poll(): void
    {
        // Reload session with fresh quiz data including questions and answers
        $this->session = QuizSession::query()->where('id', $this->session->id)->with('quiz.questions.answers')->firstOrFail();
        $this->participants = $this->session->participants()->with('user')->get();
        $participant = SessionParticipant::query()->where('session_id', $this->session->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($participant === null) {
            return;
        }

        $currentQuestion = $this->session->currentQuestion();
        $this->submitted = $currentQuestion !== null && SessionAnswer::query()->where('session_id', $this->session->id)
            ->where('participant_id', $participant->id)
            ->where('question_id', $currentQuestion->id)
            ->exists();
    }

    public function submitAnswer(int $answerId): void
    {
        $participant = SessionParticipant::query()->where('session_id', $this->session->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($participant === null) {
            return;
        }

        $currentQuestion = $this->session->currentQuestion();
        if ($currentQuestion === null) {
            return;
        }

        // Prevent answering if the host has already revealed the answer
        if ($this->session->show_answer) {
            return;
        }

        // Prevent answering if the participant already submitted an answer for this question
        if (SessionAnswer::query()->where('session_id', $this->session->id)
            ->where('participant_id', $participant->id)
            ->where('question_id', $currentQuestion->id)
            ->exists()
        ) {
            return;
        }

        // Prevent answering if the answer belongs to a different question
        $selectedAnswer = Answer::query()->find($answerId);
        if ($selectedAnswer === null || $selectedAnswer->question_id !== $currentQuestion->id) {
            return;
        }

        $isCorrect = $selectedAnswer->is_correct;

        try {
            SessionAnswer::query()->create([
                'session_id'     => $this->session->id,
                'participant_id' => $participant->id,
                'question_id'    => $currentQuestion->id,
                'answer_id'      => $answerId,
                'is_correct'     => $isCorrect,
                'submitted_at'   => now(),
            ]);

            $this->selectedAnswer = $answerId;
            $this->submitted = true;
        } catch (UniqueConstraintViolationException) {
            // Already answered due to race condition - ignore
            $this->submitted = true;
        }
    }

    /**
     * Get the avatar URL for a participant using ui-avatars.com
     */
    public function getAvatarUrl(string $name, int $size = 128): string
    {
        return Avatars::url($name, $size);
    }

    public function render(): View
    {
        return view('livewire.quiz-session-participant')->layout('components.layouts.sidebar', ['title' => 'Quiz Session']);
    }
}
