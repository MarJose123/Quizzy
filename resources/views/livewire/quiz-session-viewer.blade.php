<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-indigo-50/30 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/20" wire:poll.2s="poll">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-indigo-600 focus:text-white focus:rounded-md">
        Skip to main content
    </a>

    <div id="main-content" class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        @if($session->status === \App\Enums\SessionStatus::Waiting)
            {{-- Waiting --}}
            <div class="text-center py-16 sm:py-20">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/40 dark:to-purple-900/40 rounded-2xl mb-6">
                    <x-mary-icon name="o-arrow-path" class="w-10 h-10 animate-spin text-indigo-500" />
                </div>
                <h2 class="text-2xl font-bold tracking-tight mb-2">Waiting for Host to Start</h2>
                <p class="text-gray-500 dark:text-gray-400">The quiz will begin shortly...</p>
            </div>
        @elseif($session->status === \App\Enums\SessionStatus::Active)
            {{-- Active Quiz --}}
            <div class="space-y-8">
                {{-- Question Card --}}
                <x-mary-card class="shadow-xl shadow-gray-900/5 dark:shadow-gray-900/30">
                    <h2 class="text-2xl font-bold tracking-tight mb-8">{{ $session->quiz->questions[$session->current_question_index]?->text ?? 'No Question' }}</h2>

                    @if($session->show_answer && $session->quiz->questions[$session->current_question_index])
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($session->quiz->questions[$session->current_question_index]->answers as $answer)
                                <x-mary-card :class="$answer->is_correct ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : ''">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg {{ $answer->is_correct ? 'bg-green-100 dark:bg-green-900/40' : 'bg-gray-100 dark:bg-gray-800' }} flex items-center justify-center text-sm font-semibold {{ $answer->is_correct ? 'text-green-600 dark:text-green-400' : 'text-gray-500' }}">
                                                {{ $loop->index + 1 }}
                                            </div>
                                            <p class="font-medium">{{ $answer->text }}</p>
                                        </div>
                                        @if($answer->is_correct)
                                            <x-mary-badge class="badge-success" icon="o-check" size="sm">Correct</x-mary-badge>
                                        @endif
                                    </div>
                                </x-mary-card>
                            @endforeach
                        </div>
                    @endif
                </x-mary-card>
            </div>
        @else
            {{-- Finished --}}
            <div class="text-center py-16 sm:py-20">
                <div class="mx-auto w-20 h-20 bg-gradient-to-br from-emerald-100 to-green-100 dark:from-emerald-900/40 dark:to-green-900/40 rounded-2xl flex items-center justify-center mb-6">
                    <x-mary-icon name="o-check" class="w-10 h-10 text-emerald-500" />
                </div>
                <h2 class="text-2xl font-bold tracking-tight mb-2">Session Finished</h2>
                <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">The session has ended. Thanks for watching!</p>
                <x-mary-button class="btn-primary shadow-lg shadow-indigo-500/25" :link="route('dashboard')" icon="o-home">Back to Dashboard</x-mary-button>
            </div>
        @endif
    </div>
</div>
