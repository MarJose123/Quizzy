<?php

namespace App\Livewire;

use App\Enums\SessionStatus;
use App\Enums\UserRole;
use App\Models\QuizSession;
use App\Models\SessionParticipant;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizSessionJoin extends Component
{
    public ?QuizSession $session = null;

    public ?string $error = null;

    public function mount(string $token): void
    {
        $session = QuizSession::where('token', $token)->with('quiz')->first();

        if (! $session) {
            $this->error = 'This session could not be found.';

            return;
        }

        if ($session->status === SessionStatus::Finished) {
            $this->error = 'This session has already finished.';

            return;
        }

        $this->session = $session;

        if (Auth::check()) {
            $existing = SessionParticipant::where('session_id', $session->id)
                ->where('user_id', Auth::id())
                ->first();

            if ($existing) {
                if ($existing->role === UserRole::Viewer) {
                    redirect()->to(route('session.viewer', $token));
                } else {
                    redirect()->to(route('session.participant', $token));
                }
            }
        }
    }

    public function join(): void
    {
        if (! $this->session) {
            return;
        }

        SessionParticipant::create([
            'session_id' => $this->session->id,
            'user_id'    => Auth::id(),
            'role'       => UserRole::Participant,
            'joined_at'  => now(),
        ]);

        redirect()->to(route('session.participant', $this->session->token));
    }

    public function render()
    {
        return view('livewire.quiz-session-join');
    }
}
