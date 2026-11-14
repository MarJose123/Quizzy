<?php

namespace App\Livewire;

use App\Enums\QuestionType;
use App\Enums\SessionStatus;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class QuizEditor extends Component
{
    public ?Quiz $quiz = null;

    public string $title = '';

    public string $description = '';

    /** @var array<int, array{id: int|null, text: string, type: string, points: int, answers: array<int, array{id: int|null, text: string, is_correct: bool}>}> */
    public array $questions = [];

    /** Whether the add/edit question dialog is open. */
    public bool $showQuestionDialog = false;

    /** Index of the question being edited, or null when adding a new question. */
    public ?int $editingQuestionIndex = null;

    /** Form data for the dialog question. */
    public string $dialogQuestionText = '';

    public string $dialogQuestionType = 'multiple_choice';

    public int $dialogQuestionPoints = 10;

    /** @var array<int, array{id: int|null, text: string, is_correct: bool}> */
    public array $dialogAnswers = [];

    /** Index of the correct answer in the dialog for radio selection. */
    public ?int $dialogCorrectAnswerIndex = null;

    /** Whether the delete confirmation dialog is open. */
    public bool $showDeleteConfirm = false;

    /** Index of the question pending deletion. */
    public ?int $deletingQuestionIndex = null;

    public function mount(?Quiz $quiz = null): void
    {
        $this->quiz = $quiz;

        $user = Auth::user();

        if ($quiz && $quiz->created_by !== Auth::id() && ($user === null || ! $user->isAdmin())) {
            redirect()->to(route('dashboard'))->with('error', 'You do not have permission to edit this quiz.');

            return;
        }

        if ($quiz) {
            $this->title = $quiz->title;
            $this->description = $quiz->description ?? '';
            $this->questions = $quiz->questions()->orderBy('order')->get()->map(fn ($question) => [
                'id'      => $question->id,
                'text'    => $question->text,
                'type'    => $question->type->value,
                'points'  => $question->points ?? 10,
                'answers' => $question->answers->map(fn ($a) => [
                    'id'         => $a->id,
                    'text'       => $a->text,
                    'is_correct' => $a->is_correct,
                ])->all(),
            ])->all();
        } else {
            $this->questions = [];
        }
    }

    /** Open the dialog to add a new question. */
    public function openAddQuestionDialog(): void
    {
        $this->editingQuestionIndex = null;
        $this->resetDialogForm();
        $this->showQuestionDialog = true;
    }

    /** Open the dialog to edit an existing question. */
    public function openEditQuestionDialog(int $index): void
    {
        $this->editingQuestionIndex = $index;
        $question = $this->questions[$index];

        $this->dialogQuestionText = $question['text'];
        $this->dialogQuestionType = $question['type'];
        $this->dialogQuestionPoints = $question['points'];
        $this->dialogAnswers = $question['answers'];

        // Find the correct answer index for radio selection
        $this->dialogCorrectAnswerIndex = null;
        foreach ($this->dialogAnswers as $aIndex => $answer) {
            if ($answer['is_correct']) {
                $this->dialogCorrectAnswerIndex = $aIndex;
                break;
            }
        }

        $this->showQuestionDialog = true;
    }

    public function closeQuestionDialog(): void
    {
        $this->showQuestionDialog = false;
        $this->editingQuestionIndex = null;
        $this->resetDialogForm();
    }

    /** Reset the dialog form to default values for a new multiple-choice question. */
    public function resetDialogForm(): void
    {
        $this->dialogQuestionText = '';
        $this->dialogQuestionType = QuestionType::MultipleChoice->value;
        $this->dialogQuestionPoints = 10;
        $this->dialogCorrectAnswerIndex = null;
        $this->dialogAnswers = [
            ['id' => null, 'text' => '', 'is_correct' => false],
            ['id' => null, 'text' => '', 'is_correct' => false],
            ['id' => null, 'text' => '', 'is_correct' => false],
            ['id' => null, 'text' => '', 'is_correct' => false],
        ];
    }

    /** Lifecycle hook: called when dialogQuestionType changes via wire:model. */
    public function updatedDialogQuestionType(): void
    {
        if ($this->dialogQuestionType === QuestionType::TrueFalse->value) {
            $this->dialogAnswers = [
                ['id' => null, 'text' => 'True', 'is_correct' => false],
                ['id' => null, 'text' => 'False', 'is_correct' => false],
            ];
        } else {
            $this->dialogAnswers = [
                ['id' => null, 'text' => '', 'is_correct' => false],
                ['id' => null, 'text' => '', 'is_correct' => false],
                ['id' => null, 'text' => '', 'is_correct' => false],
                ['id' => null, 'text' => '', 'is_correct' => false],
            ];
        }

        $this->dialogCorrectAnswerIndex = null;
    }

    /** Lifecycle hook: called when dialogCorrectAnswerIndex changes via wire:model. */
    public function updatedDialogCorrectAnswerIndex(): void
    {
        foreach ($this->dialogAnswers as $aIndex => &$answer) {
            $answer['is_correct'] = ($aIndex === $this->dialogCorrectAnswerIndex);
        }
        unset($answer);
    }

    /** Toggle the question type in the dialog and reset answers accordingly. */
    public function toggleDialogQuestionType(string $type): void
    {
        $this->dialogQuestionType = $type;
        $this->updatedDialogQuestionType();
    }

    /** Add an answer option in the dialog (for multiple choice). */
    public function addDialogAnswer(): void
    {
        $this->dialogAnswers[] = ['id' => null, 'text' => '', 'is_correct' => false];
    }

    /** Remove an answer option from the dialog. */
    public function removeDialogAnswer(int $index): void
    {
        unset($this->dialogAnswers[$index]);
        $this->dialogAnswers = array_values($this->dialogAnswers);
    }

    /** Save the question from the dialog to the questions list. */
    public function saveQuestion(): void
    {
        $validated = $this->validate([
            'dialogQuestionText'   => ['required', 'string'],
            'dialogQuestionType'   => ['required', 'in:' . QuestionType::MultipleChoice->value . ',' . QuestionType::TrueFalse->value],
            'dialogQuestionPoints' => ['required', 'integer', 'min:1', 'max:100'],
            'dialogAnswers.*.text' => ['required', 'string'],
        ], [
            'dialogQuestionText.required'   => 'Question text is required.',
            'dialogQuestionPoints.required' => 'Points are required.',
            'dialogQuestionPoints.min'      => 'Points must be at least 1.',
            'dialogQuestionPoints.max'      => 'Points must not exceed 100.',
            'dialogAnswers.*.text.required' => 'Answer text is required.',
        ]);
        $hasCorrect = array_any($this->dialogAnswers, fn ($a) => $a['is_correct']);

        if (! $hasCorrect) {
            $this->addError('dialogAnswers', 'Each question must have a correct answer.');

            return;
        }

        $questionData = [
            'id'      => null,
            'text'    => $validated['dialogQuestionText'],
            'type'    => $validated['dialogQuestionType'],
            'points'  => $validated['dialogQuestionPoints'],
            'answers' => $this->dialogAnswers,
        ];

        if ($this->editingQuestionIndex !== null) {
            $questionData['id'] = $this->questions[$this->editingQuestionIndex]['id'];
            $this->questions[$this->editingQuestionIndex] = $questionData;
        } else {
            $this->questions[] = $questionData;
        }

        $this->closeQuestionDialog();
    }

    /** Open the confirmation dialog before removing a question. */
    public function confirmDeleteQuestion(int $index): void
    {
        $this->deletingQuestionIndex = $index;
        $this->showDeleteConfirm = true;
    }

    /** Confirm and remove the question. */
    public function confirmDelete(): void
    {
        if ($this->deletingQuestionIndex !== null) {
            unset($this->questions[$this->deletingQuestionIndex]);
            $this->questions = array_values($this->questions);
        }

        $this->closeDeleteConfirm();
    }

    /** Close the delete confirmation dialog. */
    public function closeDeleteConfirm(): void
    {
        $this->showDeleteConfirm = false;
        $this->deletingQuestionIndex = null;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        if (empty($this->questions)) {
            $this->addError('questions', 'Add at least one question to the quiz.');

            return;
        }

        // Prevent editing if the quiz has active sessions
        if ($this->quiz && $this->quiz->sessions()
            ->whereIn('status', [SessionStatus::Waiting->value, SessionStatus::Active->value])
            ->exists()
        ) {
            $this->addError('quiz', 'Cannot edit a quiz with active sessions. Finish or end all sessions first.');

            return;
        }

        DB::transaction(function () use ($validated) {
            if ($this->quiz) {
                $quiz = $this->quiz;
                $quiz->update([
                    'title'       => $validated['title'],
                    'description' => $validated['description'],
                ]);

                $quiz->questions()->delete();
            } else {
                $quiz = Quiz::query()->create([
                    'created_by'  => Auth::id(),
                    'title'       => $validated['title'],
                    'description' => $validated['description'],
                ]);
            }

            foreach ($this->questions as $order => $questionData) {
                $question = Question::query()->create([
                    'quiz_id' => $quiz->id,
                    'text'    => $questionData['text'],
                    'type'    => $questionData['type'],
                    'points'  => $questionData['points'],
                    'order'   => $order,
                ]);

                foreach ($questionData['answers'] as $answerData) {
                    Answer::query()->create([
                        'question_id' => $question->id,
                        'text'        => $answerData['text'],
                        'is_correct'  => $answerData['is_correct'],
                    ]);
                }
            }
        });

        redirect()->to(route('dashboard'));
    }

    public function cancel(): void
    {
        redirect()->to(route('dashboard'));
    }

    public function render(): View
    {
        return view('livewire.quiz-editor')->layout('components.layouts.sidebar', ['title' => ($this->quiz ? 'Edit' : 'Create') . ' Quiz']);
    }
}
