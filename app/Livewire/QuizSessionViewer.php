<?php

namespace App\Livewire;

use App\Enums\UserRole;
use App\Models\QuizSession;
use App\Models\SessionParticipant;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizSessionViewer extends Component
{
    public QuizSession $session;

    public function mount(string $token): void
    {
        $session = QuizSession::query()->where('token', $token)->firstOrFail();
        $this->session = $session;

        $participant = SessionParticipant::query()->where('session_id', $session->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($participant === null) {
            redirect()->to(route('session.join', $token));

            return;
        }

        if ($participant->role !== UserRole::Viewer) {
            redirect()->to(route('session.participant', $token));

            return;
        }
    }

    public function poll(): void
    {
        $this->session->refresh();
    }

    public function render(): View
    {
        return view('livewire.quiz-session-viewer')->layout('components.layouts.sidebar', ['title' => 'View Session']);
    }
}
