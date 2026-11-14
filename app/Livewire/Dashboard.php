<?php

namespace App\Livewire;

use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public string $search = '';

    public function quizzes()
    {
        $query = Quiz::withCount('questions')->with('creator');

        if (! Auth::user()->isAdmin()) {
            $query->where('created_by', Auth::id());
        }

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        return $query->latest()->paginate(15);
    }

    public function createQuiz(): void
    {
        redirect()->to(route('quiz.create'));
    }

    public function deleteQuiz(Quiz $quiz): void
    {
        if ($quiz->created_by !== Auth::id() && ! Auth::user()->isAdmin()) {
            return;
        }

        $quiz->delete();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
