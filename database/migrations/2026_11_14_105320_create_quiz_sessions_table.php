<?php

use App\Enums\SessionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->restrictOnDelete();
            $table->foreignId('host_id')->constrained('users')->restrictOnDelete();
            $table->string('token')->unique();
            $table->unsignedInteger('current_question_index')->default(0);
            $table->string('status')->default(SessionStatus::Waiting->value);
            $table->boolean('show_answer')->default(false);
            $table->boolean('show_scores')->default(false);
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            $table->index('token');
            $table->index(['quiz_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_sessions');
    }
};
