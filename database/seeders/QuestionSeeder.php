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
        $quiz1 = Quiz::where('title', 'General Knowledge Quiz')->firstOrFail();
        $quiz2 = Quiz::where('title', 'Technology Trivia')->firstOrFail();

        // Quiz 1: General Knowledge questions
        $q1 = Question::factory()->create([
            'quiz_id' => $quiz1->id,
            'text'    => 'What is the capital of France?',
            'type'    => QuestionType::MultipleChoice,
            'points'  => 10,
            'order'   => 0,
        ]);

        $q2 = Question::factory()->create([
            'quiz_id' => $quiz1->id,
            'text'    => 'Which planet is known as the Red Planet?',
            'type'    => QuestionType::MultipleChoice,
            'points'  => 10,
            'order'   => 1,
        ]);

        $q3 = Question::factory()->create([
            'quiz_id' => $quiz1->id,
            'text'    => 'The Great Wall of China is visible from space.',
            'type'    => QuestionType::TrueFalse,
            'points'  => 10,
            'order'   => 2,
        ]);

        // Quiz 2: Technology questions
        $q4 = Question::factory()->create([
            'quiz_id' => $quiz2->id,
            'text'    => 'What does HTML stand for?',
            'type'    => QuestionType::MultipleChoice,
            'points'  => 10,
            'order'   => 0,
        ]);

        $q5 = Question::factory()->create([
            'quiz_id' => $quiz2->id,
            'text'    => 'Python is a compiled programming language.',
            'type'    => QuestionType::TrueFalse,
            'points'  => 10,
            'order'   => 1,
        ]);

        $q6 = Question::factory()->create([
            'quiz_id' => $quiz2->id,
            'text'    => 'Which company developed the Laravel framework?',
            'type'    => QuestionType::MultipleChoice,
            'points'  => 10,
            'order'   => 2,
        ]);
    }
}
