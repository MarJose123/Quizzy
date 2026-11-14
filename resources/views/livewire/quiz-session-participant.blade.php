<x-layouts.app :title="'Quiz Session'">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
        <nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold">Quizzy</h1>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ auth()->user()->name }}</span>
                        <img
                            src="{{ $this->getAvatarUrl(auth()->user()->name, 40) }}"
                            alt="{{ auth()->user()->name }}"
                            class="w-10 h-10 rounded-full"
                        >
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if($session->status === \App\Enums\SessionStatus::Waiting)
                {{-- Waiting Lobby --}}
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold">{{ $session->quiz->title }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Waiting for the host to start the quiz...</p>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Participants ({{ $participants->count() }})</h3>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($participants as $participant)
                            <div class="flex flex-col items-center gap-2 p-4 rounded-lg {{ $participant->user_id === auth()->id() ? 'bg-indigo-50 dark:bg-indigo-900/20 ring-2 ring-indigo-500' : 'bg-gray-50 dark:bg-gray-800' }}">
                                <img
                                    src="{{ $this->getAvatarUrl($participant->user->name) }}"
                                    alt="{{ $participant->user->name }}"
                                    class="w-16 h-16 rounded-full"
                                >
                                <div class="text-center">
                                    <p class="text-sm font-medium truncate w-24">{{ $participant->user->name }}</p>
                                    @if($participant->role === \App\Enums\UserRole::Host)
                                        <span class="inline-block px-2 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 rounded-full">
                                            Host
                                        </span>
                                    @else
                                        <span class="inline-block px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 rounded-full">
                                            Participant
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <div class="inline-flex items-center gap-2 text-gray-500 dark:text-gray-400">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Waiting for host to start...</span>
                    </div>
                </div>
            @elseif($session->status === \App\Enums\SessionStatus::Active)
                {{-- Active Quiz --}}
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                Question {{ $session->current_question_index + 1 }} of {{ $session->quiz->questions->count() }}
                            </span>
                        </div>

                        <h2 class="text-2xl font-bold mb-6">
                            {{ $session->quiz->questions[$session->current_question_index]?->text ?? 'No Question' }}
                        </h2>

                        @if(!$submitted)
                            <div class="space-y-3">
                                @if($session->quiz->questions[$session->current_question_index])
                                    @foreach($session->quiz->questions[$session->current_question_index]->answers as $answer)
                                        <button
                                            wire:click="submitAnswer({{ $answer->id }})"
                                            class="w-full text-left p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-indigo-50 dark:hover:bg-gray-800 transition-colors"
                                        >
                                            {{ $answer->text }}
                                        </button>
                                    @endforeach
                                @endif
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="mx-auto w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <p class="text-lg text-green-600 dark:text-green-400">Answer submitted!</p>
                                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Waiting for host to continue...</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                {{-- Finished --}}
                <div class="text-center py-12">
                    <div class="mx-auto w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold">Session Finished</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Thank you for participating!</p>
                    <a href="{{ route('dashboard') }}" class="mt-6 inline-block px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition-colors">
                        Back to Dashboard
                    </a>
                </div>
            @endif
        </div>

        @wire('poll')
    </div>
</x-layouts.app>
