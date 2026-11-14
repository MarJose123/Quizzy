<?php

namespace App\Livewire;

use App\Enums\QuestionType;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class QuizEditor extends Component
{
    public ?Quiz $quiz = null;

    public string $title = '';

    public string $description = '';

    /** @var array<int, array{?id: int|null, text: string, type: string, answers: array<int, array{id: int|null, text: string, is_correct: bool}>}> */
    public array $questions = [];

    public function mount(?Quiz $quiz = null): void
    {
        $this->quiz = $quiz;

        if ($quiz && $quiz->created_by !== Auth::id() && ! Auth::user()->isAdmin()) {
            redirect()->to(route('dashboard'))->with('error', 'You do not have permission to edit this quiz.');

            return;
        }

        if ($quiz) {
            $this->title = $quiz->title;
            $this->description = $quiz->description ?? '';
            $this->questions = $quiz->questions()->orderBy('order')->get()->map(function ($question) {
                return [
                    'id'      => $question->id,
                    'text'    => $question->text,
                    'type'    => $question->type->value,
                    'answers' => $question->answers->map(fn ($a) => [
                        'id'         => $a->id,
                        'text'       => $a->text,
                        'is_correct' => $a->is_correct,
                    ])->toArray(),
                ];
            })->toArray();
        } else {
            $this->questions = [];
        }
    }

    public function addQuestion(): void
    {
        $this->questions[] = [
            'id'      => null,
            'text'    => '',
            'type'    => QuestionType::MultipleChoice->value,
            'answers' => [
                ['id' => null, 'text' => '', 'is_correct' => false],
                ['id' => null, 'text' => '', 'is_correct' => false],
                ['id' => null, 'text' => '', 'is_correct' => false],
                ['id' => null, 'text' => '', 'is_correct' => false],
            ],
        ];
    }

    public function removeQuestion(int $index): void
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    public function toggleQuestionType(int $index, string $type): void
    {
        $this->questions[$index]['type'] = $type;

        if ($type === QuestionType::TrueFalse->value) {
            $this->questions[$index]['answers'] = [
                ['id' => null, 'text' => 'True', 'is_correct' => false],
                ['id' => null, 'text' => 'False', 'is_correct' => false],
            ];
        } else {
            $this->questions[$index]['answers'] = [
                ['id' => null, 'text' => '', 'is_correct' => false],
                ['id' => null, 'text' => '', 'is_correct' => false],
                ['id' => null, 'text' => '', 'is_correct' => false],
                ['id' => null, 'text' => '', 'is_correct' => false],
            ];
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title'                      => ['required', 'string', 'max:255'],
            'description'                => ['nullable', 'string', 'max:1000'],
            'questions.*.text'           => ['required', 'string'],
            'questions.*.type'           => ['required', 'in:' . QuestionType::MultipleChoice->value . ',' . $QuestionType::TrueFalse->value],
            'questions.*.answers.*.text' => ['required', 'string'],
        ], [
            'questions.*.text.required'           => 'Question text is required.',
            'questions.*.answers.*.text.required' => 'Answer text is required.',
        ]);

        foreach ($validated['questions'] ?? [] as $qIndex => $question) {
            $hasCorrect = false;
            foreach ($question['answers'] ?? [] as $a) {
                if ($a['is_correct']) {
                    $hasCorrect = true;
                    break;
                }
            }
            if (! $hasCorrect) {
                $this->addError('questions.' . $qIndex . '.answers', 'Each question must have a correct answer.');
            }
        }

        if ($this->hasErrors()) {
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
                $quiz = Quiz::create([
                    'created_by'  => Auth::id(),
                    'title'       => $validated['title'],
                    'description' => $validated['description'],
                ]);
            }

            foreach ($validated['questions'] ?? [] as $order => $questionData) {
                $question = Question::create([
                    'quiz_id' => $quiz->id,
                    'text'    => $questionData['text'],
                    'type'    => $questionData['type'],
                    'order'   => $order,
                ]);

                foreach ($questionData['answers'] as $answerData) {
                    Answer::create([
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

    public function render()
    {
        return view('livewire.quiz-editor');
    }
}
