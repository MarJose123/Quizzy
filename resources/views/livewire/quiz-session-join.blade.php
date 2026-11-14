<x-layouts.app :title="'Join Session'">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md p-8 space-y-6 bg-white dark:bg-gray-900 rounded-lg shadow">
            @if($error)
                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-red-600 dark:text-red-400">Oops!</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $error }}</p>
                    <a href="{{ route('home') }}" class="mt-6 inline-block text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                        Back to Home
                    </a>
                </div>
            @elseif(auth()->guest())
                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold">Join Quiz</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        {{ $session ? $session->quiz->title : 'Quiz Session' }}
                    </p>
                </div>

                <div class="space-y-3">
                    <a href="{{ route('socialite.redirect', ['provider' => 'zoho', 'session_token' => request()->route('token')]) }}"
                       class="flex items-center justify-center gap-3 w-full py-3 px-4 bg-[#FF6F00] hover:bg-[#e66300] text-white rounded-md text-lg font-medium transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                        Continue with Zoho
                    </a>

                    <a href="{{ route('socialite.redirect', ['provider' => 'microsoft', 'session_token' => request()->route('token')]) }}"
                       class="flex items-center justify-center gap-3 w-full py-3 px-4 bg-[#0078D4] hover:bg-[#006cbd] text-white rounded-md text-lg font-medium transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.4 24H0V12.6h11.4V24zM24 24H12.6V12.6H24V24zM11.4 11.4H0V0h11.4v11.4zM24 11.4H12.6V0H24v11.4z"/>
                        </svg>
                        Continue with Microsoft
                    </a>
                </div>
            @else
                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold">Join Quiz</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        {{ $session ? $session->quiz->title : 'Quiz Session' }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">
                        Signed in as {{ auth()->user()->name }}
                    </p>
                </div>

                <button wire:click="join"
                        class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-lg font-medium transition-colors">
                    Join as Participant
                </button>
            @endif
        </div>
    </div>
</x-layouts.app>
