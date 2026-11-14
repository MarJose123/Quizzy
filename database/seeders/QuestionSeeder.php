<?php

namespace Database\Seeders;

use App\Enums\QuestionType;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quiz1 = Quiz::where('title', 'General Knowledge Quiz')->first();
        $quiz2 = Quiz::where('title', 'Technology Trivia')->first();

        // Quiz 1: General Knowledge questions
        $q1 = Question::factory()->create([
            'quiz_id' => $quiz1->id,
            'text'    => 'What is the capital of France?',
            'type'    => QuestionType::MultipleChoice,
            'order'   => 1,
        ]);

        $q2 = Question::factory()->create([
            'quiz_id' => $quiz1->id,
            'text'    => 'Which planet is known as the Red Planet?',
            'type'    => QuestionType::MultipleChoice,
            'order'   => 2,
        ]);

        $q3 = Question::factory()->create([
            'quiz_id' => $quiz1->id,
            'text'    => 'The Great Wall of China is visible from space.',
            'type'    => QuestionType::TrueFalse,
            'order'   => 3,
        ]);

        // Quiz 2: Technology questions
        $q4 = Question::factory()->create([
            'quiz_id' => $quiz2->id,
            'text'    => 'What does HTML stand for?',
            'type'    => QuestionType::MultipleChoice,
            'order'   => 1,
        ]);

        $q5 = Question::factory()->create([
            'quiz_id' => $quiz2->id,
            'text'    => 'Python is a compiled programming language.',
            'type'    => QuestionType::TrueFalse,
            'order'   => 2,
        ]);

        $q6 = Question::factory()->create([
            'quiz_id' => $quiz2->id,
            'text'    => 'Which company developed the Laravel framework?',
            'type'    => QuestionType::MultipleChoice,
            'order'   => 3,
        ]);
    }
}
