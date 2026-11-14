<x-layouts.app :title="'Join Session'">
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-indigo-50/30 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/20">
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-indigo-600 focus:text-white focus:rounded-md">
            Skip to main content
        </a>

        <div id="main-content" class="flex items-center justify-center min-h-screen p-4">
            <div class="w-full max-w-md">
                @if($error)
                    {{-- Error State --}}
                    <div class="text-center">
                        <div class="mx-auto w-20 h-20 bg-gradient-to-br from-red-100 to-orange-100 dark:from-red-900/40 dark:to-orange-900/40 rounded-2xl flex items-center justify-center mb-6">
                            <x-mary-icon name="o-exclamation-triangle" class="w-10 h-10 text-red-500" />
                        </div>
                        <h2 class="text-2xl font-bold text-red-600 dark:text-red-400 tracking-tight mb-2">Oops!</h2>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">{{ $error }}</p>
                        <x-mary-button class="btn-ghost" :link="route('home')" icon="o-home">Back to Home</x-mary-button>
                    </div>
                @elseif(auth()->guest())
                    {{-- Guest - Sign In Required --}}
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/40 dark:to-purple-900/40 rounded-2xl mb-5">
                            <x-mary-icon name="o-user-group" class="w-8 h-8 text-indigo-500" />
                        </div>
                        <h1 class="text-2xl font-bold tracking-tight mb-2">Join Quiz</h1>
                        <p class="text-gray-500 dark:text-gray-400">{{ $session ? $session->quiz->title : 'Quiz Session' }}</p>
                    </div>

                    <x-mary-card class="shadow-xl shadow-gray-900/5 dark:shadow-gray-900/30 mb-4">
                        <div class="space-y-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-2">Sign in to continue</p>

                            <a href="{{ route('socialite.redirect', ['provider' => 'zoho', 'session_token' => request()->route('token')]) }}"
                               class="flex items-center justify-center gap-3 w-full py-3 px-4 bg-[#FF6F00] hover:bg-[#e66300] text-white rounded-lg text-lg font-medium transition-colors">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                                </svg>
                                Continue with Zoho
                            </a>

                            <a href="{{ route('socialite.redirect', ['provider' => 'microsoft', 'session_token' => request()->route('token')]) }}"
                               class="flex items-center justify-center gap-3 w-full py-3 px-4 bg-[#0078D4] hover:bg-[#006cbd] text-white rounded-lg text-lg font-medium transition-colors">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M11.4 24H0V12.6h11.4V24zM24 24H12.6V12.6H24V24zM11.4 11.4H0V0h11.4v11.4zM24 11.4H12.6V0H24v11.4z"/>
                                </svg>
                                Continue with Microsoft
                            </a>
                        </div>
                    </x-mary-card>
                @else
                    {{-- Authenticated - Join --}}
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/40 dark:to-purple-900/40 rounded-2xl mb-5">
                            <x-mary-icon name="o-arrow-right" class="w-8 h-8 text-indigo-500" />
                        </div>
                        <h1 class="text-2xl font-bold tracking-tight mb-2">Join Quiz</h1>
                        <p class="text-gray-500 dark:text-gray-400">{{ $session ? $session->quiz->title : 'Quiz Session' }}</p>
                    </div>

                    <x-mary-card class="shadow-xl shadow-gray-900/5 dark:shadow-gray-900/30">
                        <div class="flex items-center gap-3 mb-5 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <x-mary-avatar :image="$this->getAvatarUrl(auth()->user()->name)" :placeholder="substr(auth()->user()->name, 0, 1)" />
                            <div class="text-left">
                                <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Signed in</p>
                            </div>
                        </div>

                        <x-mary-button wire:click="join" class="btn-primary w-full shadow-lg shadow-indigo-500/25" icon="o-user-plus" wire:loading.attr="disabled" wire:target="join">Join as Participant</x-mary-button>
                    </x-mary-card>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
