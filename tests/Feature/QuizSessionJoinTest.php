<?php

use App\Enums\SessionStatus;
use App\Enums\UserRole;
use App\Livewire\QuizSessionJoin;
use App\Models\Quiz;
use App\Models\QuizSession;
use App\Models\SessionParticipant;
use App\Models\User;

use function Pest\Laravel\actingAs;

/*
|--------------------------------------------------------------------------
| QuizSessionJoin Tests
|--------------------------------------------------------------------------
*/

test('guest cannot join and sees login prompt', function () {
    $host = User::factory()->create();
    $quiz = Quiz::factory()->create(['created_by' => $host->id]);
    $session = QuizSession::factory()->create([
        'quiz_id' => $quiz->id,
        'host_id' => $host->id,
    ]);

    $this->get(route('session.join', ['token' => $session->token]))
        ->assertOk()
        ->assertSee('Sign in to continue');
});

test('participant can join waiting session', function () {
    $host = User::factory()->create();
    $participant = User::factory()->create();
    $quiz = Quiz::factory()->create(['created_by' => $host->id]);
    $session = QuizSession::factory()->create([
        'quiz_id' => $quiz->id,
        'host_id' => $host->id,
        'status'  => SessionStatus::Waiting,
    ]);

    actingAs($participant);

    Livewire::test(QuizSessionJoin::class, ['token' => $session->token])
        ->call('join');

    $this->assertDatabaseHas('session_participants', [
        'session_id' => $session->id,
        'user_id'    => $participant->id,
    ]);
});

test('participant cannot join active session', function () {
    $host = User::factory()->create();
    $participant = User::factory()->create();
    $quiz = Quiz::factory()->create(['created_by' => $host->id]);
    $session = QuizSession::factory()->active()->create([
        'quiz_id' => $quiz->id,
        'host_id' => $host->id,
    ]);

    actingAs($participant);

    Livewire::test(QuizSessionJoin::class, ['token' => $session->token])
        ->assertSee('This session has already started');

    $this->assertDatabaseMissing('session_participants', [
        'session_id' => $session->id,
        'user_id'    => $participant->id,
    ]);
});

test('participant cannot join finished session', function () {
    $host = User::factory()->create();
    $participant = User::factory()->create();
    $quiz = Quiz::factory()->create(['created_by' => $host->id]);
    $session = QuizSession::factory()->finished()->create([
        'quiz_id' => $quiz->id,
        'host_id' => $host->id,
    ]);

    actingAs($participant);

    Livewire::test(QuizSessionJoin::class, ['token' => $session->token])
        ->assertSee('This session has already finished');

    $this->assertDatabaseMissing('session_participants', [
        'session_id' => $session->id,
        'user_id'    => $participant->id,
    ]);
});

test('participant cannot join after session becomes active', function () {
    $host = User::factory()->create();
    $participant = User::factory()->create();
    $quiz = Quiz::factory()->create(['created_by' => $host->id]);
    $session = QuizSession::factory()->create([
        'quiz_id' => $quiz->id,
        'host_id' => $host->id,
        'status'  => SessionStatus::Waiting,
    ]);

    actingAs($participant);

    $component = Livewire::test(QuizSessionJoin::class, ['token' => $session->token]);

    // Host starts the session while participant is on the join page
    $session->update(['status' => SessionStatus::Active]);

    $component->call('join');

    $this->assertDatabaseMissing('session_participants', [
        'session_id' => $session->id,
        'user_id'    => $participant->id,
    ]);
});

test('already joined participant is redirected to participant page', function () {
    $host = User::factory()->create();
    $participant = User::factory()->create();
    $quiz = Quiz::factory()->create(['created_by' => $host->id]);
    $session = QuizSession::factory()->create([
        'quiz_id' => $quiz->id,
        'host_id' => $host->id,
        'status'  => SessionStatus::Waiting,
    ]);

    SessionParticipant::query()->create([
        'session_id' => $session->id,
        'user_id'    => $participant->id,
        'role'       => UserRole::Participant,
        'joined_at'  => now(),
    ]);

    actingAs($participant);

    $this->get(route('session.join', ['token' => $session->token]))
        ->assertRedirect(route('session.participant', ['token' => $session->token]));
});

test('invalid session token shows error', function () {
    actingAs(User::factory()->create());

    Livewire::test(QuizSessionJoin::class, ['token' => 'nonexistent-token'])
        ->assertSee('This session could not be found');
});
