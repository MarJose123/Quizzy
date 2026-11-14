<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@quizzy.test')->firstOrFail();

        // Sample quiz 1: General Knowledge (multiple-choice questions)
        Quiz::factory()->create([
            'created_by'  => $admin->id,
            'title'       => 'General Knowledge Quiz',
            'description' => 'Test your general knowledge with these fun questions!',
        ]);

        // Sample quiz 2: Technology (mixed question types)
        Quiz::factory()->create([
            'created_by'  => $admin->id,
            'title'       => 'Technology Trivia',
            'description' => 'How much do you know about technology?',
        ]);
    }
}
