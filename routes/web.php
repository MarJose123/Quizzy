<?php

use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Livewire\Dashboard;
use App\Livewire\Login;
use App\Livewire\Profile;
use App\Livewire\QuizEditor;
use App\Livewire\QuizSessionHost;
use App\Livewire\QuizSessionJoin;
use App\Livewire\QuizSessionParticipant;
use App\Livewire\QuizSessionViewer;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// Guest routes (login and social authentication)
Route::middleware('guest')->group(function () {
    Route::livewire('/login', Login::class)->name('login');
    Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('socialite.redirect');
    Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('socialite.callback');
});

// Protected routes (requires authentication)
Route::middleware(['auth'])->group(function () {
    Route::livewire('/profile', Profile::class)->name('profile');
    Route::post('/logout', [LogoutController::class, 'store'])->name('logout');

    Route::livewire('/dashboard', Dashboard::class)->name('dashboard');

    // Quizzes
    Route::prefix('quiz')->name('quiz.')->group(function () {
        Route::livewire('/create', QuizEditor::class)->name('create');
        Route::livewire('/{quiz}/edit', QuizEditor::class)->name('edit');
        Route::livewire('/{quiz}/host', QuizSessionHost::class)->name('host');
    });

    // Quiz Session Participant
    Route::prefix('session')->name('session.')->group(function () {
        Route::livewire('/{token}/participant', QuizSessionParticipant::class)->name('participant');
    });
});

// Public session routes
Route::prefix('session')->name('session.')->group(function () {
    Route::livewire('/{token}/join', QuizSessionJoin::class)->name('join');
    Route::livewire('/{token}/viewer', QuizSessionViewer::class)->name('viewer');
});
