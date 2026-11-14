<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Seeder;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $q1 = Question::where('text', 'What is the capital of France?')->firstOrFail();
        Answer::factory()->create(['question_id' => $q1->id, 'text' => 'London', 'is_correct' => false]);
        Answer::factory()->create(['question_id' => $q1->id, 'text' => 'Paris', 'is_correct' => true]);
        Answer::factory()->create(['question_id' => $q1->id, 'text' => 'Berlin', 'is_correct' => false]);
        Answer::factory()->create(['question_id' => $q1->id, 'text' => 'Madrid', 'is_correct' => false]);

        $q2 = Question::where('text', 'Which planet is known as the Red Planet?')->firstOrFail();
        Answer::factory()->create(['question_id' => $q2->id, 'text' => 'Venus', 'is_correct' => false]);
        Answer::factory()->create(['question_id' => $q2->id, 'text' => 'Jupiter', 'is_correct' => false]);
        Answer::factory()->create(['question_id' => $q2->id, 'text' => 'Mars', 'is_correct' => true]);
        Answer::factory()->create(['question_id' => $q2->id, 'text' => 'Saturn', 'is_correct' => false]);

        $q3 = Question::where('text', 'The Great Wall of China is visible from space.')->firstOrFail();
        Answer::factory()->create(['question_id' => $q3->id, 'text' => 'True', 'is_correct' => false]);
        Answer::factory()->create(['question_id' => $q3->id, 'text' => 'False', 'is_correct' => true]);

        $q4 = Question::where('text', 'What does HTML stand for?')->firstOrFail();
        Answer::factory()->create(['question_id' => $q4->id, 'text' => 'Hyper Text Markup Language', 'is_correct' => true]);
        Answer::factory()->create(['question_id' => $q4->id, 'text' => 'High Tech Modern Language', 'is_correct' => false]);
        Answer::factory()->create(['question_id' => $q4->id, 'text' => 'Hyper Transfer Markup Language', 'is_correct' => false]);
        Answer::factory()->create(['question_id' => $q4->id, 'text' => 'Home Tool Markup Language', 'is_correct' => false]);

        $q5 = Question::where('text', 'Python is a compiled programming language.')->firstOrFail();
        Answer::factory()->create(['question_id' => $q5->id, 'text' => 'True', 'is_correct' => false]);
        Answer::factory()->create(['question_id' => $q5->id, 'text' => 'False', 'is_correct' => true]);

        $q6 = Question::where('text', 'Which company developed the Laravel framework?')->firstOrFail();
        Answer::factory()->create(['question_id' => $q6->id, 'text' => 'Google', 'is_correct' => false]);
        Answer::factory()->create(['question_id' => $q6->id, 'text' => 'Laravel LLC', 'is_correct' => false]);
        Answer::factory()->create(['question_id' => $q6->id, 'text' => 'Taylor Otwell (independent)', 'is_correct' => true]);
        Answer::factory()->create(['question_id' => $q6->id, 'text' => 'Microsoft', 'is_correct' => false]);
    }
}
