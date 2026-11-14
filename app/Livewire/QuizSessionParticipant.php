<?php

namespace App\Livewire;

use App\Enums\UserRole;
use App\Models\QuizSession;
use App\Models\SessionAnswer;
use App\Models\SessionParticipant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizSessionParticipant extends Component
{
    public QuizSession $session;

    public Collection $participants;

    public ?int $selectedAnswer = null;

    public bool $submitted = false;

    public function mount(string $token): void
    {
        $session = QuizSession::where('token', $token)->with('quiz')->firstOrFail();
        $this->session = $session;

        $participant = SessionParticipant::where('session_id', $session->id)
            ->where('user_id', Auth::id())
            ->first();

        if (! $participant) {
            redirect()->to(route('session.join', $token));

            return;
        }

        if ($participant->role !== UserRole::Participant) {
            redirect()->to(route('session.viewer', $token));

            return;
        }

        $this->participants = $session->participants()->with('user')->get();

        $this->submitted = SessionAnswer::where('session_id', $session->id)
            ->where('participant_id', $participant->id)
            ->where('question_id', $session->current_question_index)
            ->exists();
    }

    public function poll(): void
    {
        $this->session->refresh();
        $this->participants = $this->session->participants()->with('user')->get();
        $participant = SessionParticipant::where('session_id', $this->session->id)
            ->where('user_id', Auth::id())
            ->first();
        $this->submitted = SessionAnswer::where('session_id', $this->session->id)
            ->where('participant_id', $participant->id)
            ->where('question_id', $this->session->current_question_index)
            ->exists();
    }

    public function submitAnswer(int $answerId): void
    {
        $participant = SessionParticipant::where('session_id', $this->session->id)
            ->where('user_id', Auth::id())
            ->first();

        SessionAnswer::updateOrCreate(
            [
                'session_id'     => $this->session->id,
                'participant_id' => $participant->id,
                'question_id'    => $this->session->current_question_index,
            ],
            [
                'answer_id' => $answerId,
            ]
        );

        $this->selectedAnswer = $answerId;
        $this->submitted = true;
    }

    /**
     * Get the avatar URL for a participant using ui-avatars.com
     */
    public function getAvatarUrl(string $name, int $size = 128): string
    {
        return sprintf(
            'https://ui-avatars.com/api/?name=%s&background=random&color=fff&bold=true&size=%d',
            urlencode($name),
            $size
        );
    }

    public function render()
    {
        return view('livewire.quiz-session-participant');
    }
}
