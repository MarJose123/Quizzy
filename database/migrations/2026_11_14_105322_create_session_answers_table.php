<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('quiz_sessions')->cascadeOnDelete();
            $table->foreignId('participant_id')->constrained('session_participants')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('answer_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_correct')->default(false);
            $table->timestamp('submitted_at');
            $table->timestamps();

            $table->index(['session_id', 'participant_id']);
            $table->unique(['session_id', 'participant_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_answers');
    }
};
