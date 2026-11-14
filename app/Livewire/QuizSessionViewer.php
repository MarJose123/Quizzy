<?php

namespace App\Livewire;

use App\Models\QuizSession;
use App\Models\SessionParticipant;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizSessionViewer extends Component
{
    public QuizSession $session;

    public function mount(QuizSession $session): void
    {
        $this->session = $session;

        $participant = SessionParticipant::where('session_id', $session->id)
            ->where('user_id', Auth::id())
            ->first();

        if (! $participant) {
            redirect()->to(route('session.join', $session->token));

            return;
        }

        if ($participant->role !== 'viewer') {
            redirect()->to(route('session.participant', $session->token));

            return;
        }
    }

    public function poll(): void
    {
        $this->session->refresh();
    }

    public function render()
    {
        return view('livewire.quiz-session-viewer');
    }
}
