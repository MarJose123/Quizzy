<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="appearance">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Quizzy — Live Quizzes, Instantly</title>
        <meta name="description" content="Build engaging quizzes, host live sessions, and watch participants compete in real time — no setup required.">

        <meta property="og:title" content="Quizzy — Live Quizzes, Instantly">
        <meta property="og:description" content="Build engaging quizzes, host live sessions, and watch participants compete in real time.">
        <meta property="og:type" content="website">

        <link rel="icon" href="/favicon.ico?v=2" sizes="any">
        <link rel="icon" href="/favicon.svg?v=2" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png?v=2">

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        {{-- Styles / Scripts --}}
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                @import 'tailwindcss';
            </style>
        @endif
    </head>
    <body class="bg-base-200 text-base-content antialiased">
        {{-- Skip Navigation --}}
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-indigo-600 focus:text-white focus:rounded-md">
            Skip to main content
        </a>

        {{-- Navbar --}}
        <nav class="sticky top-0 z-40 w-full backdrop-blur bg-base-100/80 border-b border-base-300" aria-label="Main navigation">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.351-1.45.988-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-1.5-4.5a15.923 15.923 0 0 1-3.348 6.67c-.35.47-.47.97-.47 1.33V15a2 2 0 0 0-2 2c-.12.048-.237.1-.352.162a15.923 15.923 0 0 1-6.67 3.348c-.47.35-.97.47-1.33.47h-.505a2 2 0 0 0-2-2c-.048-.12-.1-.237-.162-.352A15.923 15.923 0 0 1 4.67 8.652c-.35-.47-.47-.97-.47-1.33V8a2 2 0 0 0-2-2c-.048-.12-.1-.237-.162-.352A15.923 15.923 0 0 1 2.67 4.67c.47-.35.97-.47 1.33-.47h.505a2 2 0 0 0 2-2c.048-.12.1-.237.162-.352A15.923 15.923 0 0 1 8.652 2.67c.47-.35.97-.47 1.33-.47h.505a2 2 0 0 0 2 2c.048.12.1.237.162.352A15.923 15.923 0 0 1 15.33 4.67" />
                        </svg>
                        <span class="text-xl font-bold">Quizzy</span>
                    </a>

                    <div class="flex items-center gap-3">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn btn-ghost btn-sm">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Sign in</a>
                                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Get Started</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <main id="main-content" aria-label="Quizzy landing page">

            {{-- Hero Section --}}
            <section class="relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-base-100 to-secondary/5 dark:from-primary/10 dark:via-base-300 dark:to-secondary/10"></div>

                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 lg:py-40">
                    <div class="text-center max-w-4xl mx-auto">
                        <div class="badge badge-primary gap-2 mb-8">
                            <x-mary-icon name="o-globe-alt" class="w-3 h-3" />
                            Live quiz hosting platform
                        </div>

                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight mb-6">
                            Create. Host. Play.<br>
                            <span class="text-primary">Live Quizzes in Minutes.</span>
                        </h1>

                        <p class="text-lg sm:text-xl text-base-content/60 max-w-2xl mx-auto mb-10 leading-relaxed">
                            Build engaging quizzes, host live sessions, and watch participants compete in real time — no setup required.
                        </p>

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <a href="{{ route('login') }}" class="w-full sm:w-auto btn btn-primary gap-2">
                                Get Started Free
                                <x-mary-icon name="o-arrow-right" class="w-4 h-4" />
                            </a>
                            <a href="#features" class="w-full sm:w-auto btn btn-outline">Learn More</a>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Features Section --}}
            <section id="features" class="py-24 bg-base-100" aria-labelledby="features-heading">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto mb-16">
                        <h2 id="features-heading" class="text-3xl sm:text-4xl font-bold mb-4">
                            Everything you need to run live quizzes
                        </h2>
                        <p class="text-lg text-base-content/60">
                            From creation to competition, Quizzy handles it all so you can focus on engaging your audience.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Feature 1 --}}
                        <x-mary-card class="group hover:border-indigo-200 dark:hover:border-indigo-800 transition-colors">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                                    <x-mary-icon name="o-plus" class="w-6 h-6 text-primary" />
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold mb-3">Create Quizzes</h3>
                                    <p class="text-base-content/60 leading-relaxed">
                                        Build quizzes with multiple-choice questions in minutes. Add descriptions, organize questions, and get ready to host.
                                    </p>
                                </div>
                            </div>
                        </x-mary-card>

                        {{-- Feature 2 --}}
                        <x-mary-card class="group hover:border-indigo-200 dark:hover:border-indigo-800 transition-colors">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-success/10 flex items-center justify-center shrink-0">
                                    <x-mary-icon name="o-globe-alt" class="w-6 h-6 text-success" />
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold mb-3">Host Live Sessions</h3>
                                    <p class="text-base-content/60 leading-relaxed">
                                        Share a link and host interactive quiz sessions with real-time scoring. Control the pace, manage participants, and keep everyone engaged.
                                    </p>
                                </div>
                            </div>
                        </x-mary-card>

                        {{-- Feature 3 --}}
                        <x-mary-card class="group hover:border-indigo-200 dark:hover:border-indigo-800 transition-colors">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center shrink-0">
                                    <x-mary-icon name="o-user-group" class="w-6 h-6 text-secondary" />
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold mb-3">Join &amp; Play</h3>
                                    <p class="text-base-content/60 leading-relaxed">
                                        Participants join instantly with just a link — no account friction. Compete live, answer questions, and see where you rank.
                                    </p>
                                </div>
                            </div>
                        </x-mary-card>

                        {{-- Feature 4 --}}
                        <x-mary-card class="group hover:border-indigo-200 dark:hover:border-indigo-800 transition-colors">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center shrink-0">
                                    <x-mary-icon name="o-inbox" class="w-6 h-6 text-accent" />
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold mb-3">Track Results</h3>
                                    <p class="text-base-content/60 leading-relaxed">
                                        See real-time results and leaderboards as participants answer. Watch the competition unfold and celebrate the winners.
                                    </p>
                                </div>
                            </div>
                        </x-mary-card>
                    </div>
                </div>
            </section>

            {{-- How It Works Section --}}
            <section class="py-24 bg-base-200" aria-labelledby="how-it-works-heading">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto mb-16">
                        <h2 id="how-it-works-heading" class="text-3xl sm:text-4xl font-bold mb-4">
                            How it works
                        </h2>
                        <p class="text-lg text-base-content/60">
                            Get your first quiz running in three simple steps.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-5xl mx-auto">
                        {{-- Step 1 --}}
                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full btn btn-primary flex items-center justify-center text-2xl font-bold mx-auto mb-6">
                                1
                            </div>
                            <h3 class="text-xl font-semibold mb-3">Create a Quiz</h3>
                            <p class="text-base-content/60 leading-relaxed">
                                Add your title, description, and multiple-choice questions. Build your quiz in minutes.
                            </p>
                        </div>

                        {{-- Step 2 --}}
                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full btn btn-primary flex items-center justify-center text-2xl font-bold mx-auto mb-6">
                                2
                            </div>
                            <h3 class="text-xl font-semibold mb-3">Share the Link</h3>
                            <p class="text-base-content/60 leading-relaxed">
                                Start a live session and share the invite link with your participants — via chat, email, or screen.
                            </p>
                        </div>

                        {{-- Step 3 --}}
                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full btn btn-primary flex items-center justify-center text-2xl font-bold mx-auto mb-6">
                                3
                            </div>
                            <h3 class="text-xl font-semibold mb-3">Host Live</h3>
                            <p class="text-base-content/60 leading-relaxed">
                                Control the flow, reveal answers, and watch the leaderboard update in real time as people compete.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- CTA Section --}}
            <section class="py-24 bg-base-100">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-3xl sm:text-4xl font-bold mb-6">
                        Ready to host your first quiz?
                    </h2>
                    <p class="text-lg text-base-content/60 mb-10 max-w-2xl mx-auto">
                        Sign in to start creating quizzes and hosting live sessions. It takes less than a minute to get started.
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-primary gap-2">
                        Get Started Free
                        <x-mary-icon name="o-arrow-right" class="w-4 h-4" />
                    </a>
                </div>
            </section>
        </main>

        {{-- Footer --}}
        <footer class="border-t border-base-300 bg-base-100" role="contentinfo">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.351-1.45.988-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-1.5-4.5a15.923 15.923 0 0 1-3.348 6.67c-.35.47-.47.97-.47 1.33V15a2 2 0 0 0-2 2c-.12.048-.237.1-.352.162a15.923 15.923 0 0 1-6.67 3.348c-.47.35-.97.47-1.33.47h-.505a2 2 0 0 0-2-2c-.048-.12-.1-.237-.162-.352A15.923 15.923 0 0 1 4.67 8.652c-.35-.47-.47-.97-.47-1.33V8a2 2 0 0 0-2-2c-.048-.12-.1-.237-.162-.352A15.923 15.923 0 0 1 2.67 4.67c.47-.35.97-.47 1.33-.47h.505a2 2 0 0 0 2-2c.048-.12.1-.237.162-.352A15.923 15.923 0 0 1 8.652 2.67c.47-.35.97-.47 1.33-.47h.505a2 2 0 0 0 2 2c.048.12.1.237.162.352A15.923 15.923 0 0 1 15.33 4.67" />
                        </svg>
                        <span class="text-sm font-semibold">Quizzy</span>
                    </div>
                    <p class="text-sm text-base-content/50">
                        &copy; {{ date('Y') }} Quizzy. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </body>
</html>
