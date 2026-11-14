<?php

use App\Enums\UserRole;
use App\Models\Quiz;
use App\Models\QuizSession;
use App\Models\SessionParticipant;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

/*
|--------------------------------------------------------------------------
| Guest Access Tests
|--------------------------------------------------------------------------
*/

test('guest can access the join page', function () {
    $session = QuizSession::factory()->create();

    get(route('session.join', $session->token))
        ->assertOk()
        ->assertSee('Join Quiz')
        ->assertSee('Continue with Zoho')
        ->assertSee('Continue with Microsoft');
});

test('guest sees quiz title on join page', function () {
    $quiz = Quiz::factory()->create(['title' => 'My Awesome Quiz']);
    $session = QuizSession::factory()->create(['quiz_id' => $quiz->id]);

    get(route('session.join', $session->token))
        ->assertOk()
        ->assertSee('My Awesome Quiz');
});

test('guest is redirected to social provider on login click', function () {
    $session = QuizSession::factory()->create();

    Socialite::fake('zoho');

    get(route('socialite.redirect', ['provider' => 'zoho', 'session_token' => $session->token]))
        ->assertRedirectContains('zoho');
});

test('session token is stored for redirect after social login', function () {
    $session = QuizSession::factory()->create();

    Socialite::fake('zoho');

    get(route('socialite.redirect', ['provider' => 'zoho', 'session_token' => $session->token]));

    expect(session('intended_session_token'))->toBe($session->token);
});

/*
|--------------------------------------------------------------------------
| Social Login Flow Tests
|--------------------------------------------------------------------------
*/

test('after social callback participant is redirected back to join page', function () {
    $session = QuizSession::factory()->create();

    Socialite::fake('zoho', SocialiteUser::fake([
        'id'    => 'social-user-1',
        'name'  => 'Social User',
        'email' => 'social@example.com',
    ]));

    session(['intended_session_token' => $session->token]);

    get(route('socialite.callback', ['provider' => 'zoho']))
        ->assertRedirect(route('session.join', $session->token));

    expect(User::where('email', 'social@example.com')->exists())->toBeTrue();
});

test('after social callback without session token redirects to dashboard', function () {
    Socialite::fake('zoho', SocialiteUser::fake([
        'id'    => 'social-user-2',
        'name'  => 'Social User 2',
        'email' => 'social2@example.com',
    ]));

    get(route('socialite.callback', ['provider' => 'zoho']))
        ->assertRedirect(route('dashboard'));
});

/*
|--------------------------------------------------------------------------
| Authenticated User Tests
|--------------------------------------------------------------------------
*/

test('authenticated user can join the session', function () {
    $user = User::factory()->create();
    $session = QuizSession::factory()->create();

    actingAs($user);

    get(route('session.join', $session->token))
        ->assertOk()
        ->assertSee('Join as Participant');
});

test('authenticated user is auto-redirected if already joined', function () {
    $user = User::factory()->create();
    $session = QuizSession::factory()->create();

    SessionParticipant::create([
        'session_id' => $session->id,
        'user_id'    => $user->id,
        'role'       => UserRole::Participant,
        'joined_at'  => now(),
    ]);

    actingAs($user);

    get(route('session.join', $session->token))
        ->assertRedirect(route('session.participant', $session->token));
});

test('authenticated viewer is redirected to viewer page', function () {
    $user = User::factory()->create();
    $session = QuizSession::factory()->create();

    SessionParticipant::create([
        'session_id' => $session->id,
        'user_id'    => $user->id,
        'role'       => UserRole::Viewer,
        'joined_at'  => now(),
    ]);

    actingAs($user);

    get(route('session.join', $session->token))
        ->assertRedirect(route('session.viewer', $session->token));
});

/*
|--------------------------------------------------------------------------
| Edge Case Tests
|--------------------------------------------------------------------------
*/

test('invalid session token shows error', function () {
    get(route('session.join', ['token' => 'nonexistent-token']))
        ->assertOk()
        ->assertSee('This session could not be found.');
});

test('finished session shows error', function () {
    $session = QuizSession::factory()->finished()->create();

    get(route('session.join', $session->token))
        ->assertOk()
        ->assertSee('This session has already finished.');
});

/*
|--------------------------------------------------------------------------
| Participant Lobby Tests
|--------------------------------------------------------------------------
*/

test('participant sees lobby with avatars', function () {
    $host = User::factory()->host()->create();
    $participant = User::factory()->create();
    $session = QuizSession::factory()->create(['host_id' => $host->id]);

    SessionParticipant::create([
        'session_id' => $session->id,
        'user_id'    => $host->id,
        'role'       => UserRole::Host,
        'joined_at'  => now(),
    ]);

    SessionParticipant::create([
        'session_id' => $session->id,
        'user_id'    => $participant->id,
        'role'       => UserRole::Participant,
        'joined_at'  => now(),
    ]);

    actingAs($participant);

    get(route('session.participant', $session->token))
        ->assertOk()
        ->assertSee('Waiting for the host to start')
        ->assertSee($host->name)
        ->assertSee($participant->name)
        ->assertSee('Host');
});

test('non-participant is redirected to join page', function () {
    $user = User::factory()->create();
    $session = QuizSession::factory()->create();

    actingAs($user);

    get(route('session.participant', $session->token))
        ->assertRedirect(route('session.join', $session->token));
});
