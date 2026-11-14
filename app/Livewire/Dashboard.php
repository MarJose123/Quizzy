<?php

namespace App\Livewire;

use App\Models\Quiz;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public string $search = '';

    /** @return LengthAwarePaginator<int, Quiz> */
    public function quizzes(): LengthAwarePaginator
    {
        $query = Quiz::query()->withCount('questions')->with('creator');

        $user = Auth::user();

        if ($user !== null && ! $user->isAdmin()) {
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
        $user = Auth::user();

        if ($user === null || ($quiz->created_by !== Auth::id() && ! $user->isAdmin())) {
            return;
        }

        $quiz->delete();
    }

    public function render(): View
    {
        return view('livewire.dashboard')->layout('components.layouts.sidebar', ['title' => 'Dashboard']);
    }
}
