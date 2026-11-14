<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-indigo-50/30 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/20" wire:poll.3s="poll">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-indigo-600 focus:text-white focus:rounded-md">
        Skip to main content
    </a>

    <div id="main-content" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        @if($session->status === \App\Enums\SessionStatus::Waiting)
            {{-- Waiting Lobby --}}
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/40 dark:to-purple-900/40 rounded-2xl mb-5">
                    <x-mary-icon name="o-presentation-chart-bar" class="w-8 h-8 text-indigo-500" />
                </div>
                <h2 class="text-3xl font-bold tracking-tight mb-2">{{ $session->quiz->title }}</h2>
                <p class="text-gray-500 dark:text-gray-400">You're in the lobby — waiting for the host to start</p>
            </div>

            <x-mary-card class="shadow-sm mb-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-semibold">Participants</h3>
                    <x-mary-badge class="badge-sm">{{ $participants->count() }} joined</x-mary-badge>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @foreach($participants as $participant)
                        <div class="flex flex-col items-center gap-2 p-4 rounded-xl {{ $participant->user_id === auth()->id() ? 'bg-indigo-50 dark:bg-indigo-900/20 ring-2 ring-indigo-500' : 'bg-gray-50 dark:bg-gray-800' }}">
                            <x-mary-avatar :image="$this->getAvatarUrl($participant->user->name)" :placeholder="substr($participant->user->name, 0, 1)" class="w-10 h-10" />
                            <div class="text-center">
                                <p class="text-sm font-medium truncate w-24 block">{{ $participant->user->name }}</p>
                                @if($participant->role === \App\Enums\UserRole::Host)
                                    <x-mary-badge class="badge-warning badge-sm">Host</x-mary-badge>
                                @else
                                    <x-mary-badge class="badge-sm">Participant</x-mary-badge>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-mary-card>

            <div class="text-center">
                <div class="inline-flex items-center gap-3 px-5 py-3 bg-white dark:bg-gray-900 rounded-xl shadow-sm">
                    <x-mary-icon name="o-arrow-path" class="w-5 h-5 animate-spin text-indigo-500" />
                    <p class="text-gray-500 dark:text-gray-400">Waiting for host to start...</p>
                </div>
            </div>
        @elseif($session->status === \App\Enums\SessionStatus::Active)
            {{-- Active Quiz --}}
            <div class="space-y-6">
                {{-- Progress --}}
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <x-mary-badge>
                            Question {{ $session->current_question_index + 1 }} of {{ $session->quiz->questions->count() }}
                        </x-mary-badge>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ round((($session->current_question_index + 1) / $session->quiz->questions->count()) * 100) }}% complete</p>
                    </div>
                    <div class="w-full h-2 bg-gray-200 dark:bg-gray-800 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full transition-all duration-500" style="width: {{ (($session->current_question_index + 1) / $session->quiz->questions->count()) * 100 }}%"></div>
                    </div>
                </div>

                <x-mary-card class="shadow-xl shadow-gray-900/5 dark:shadow-gray-900/30">
                    <h2 class="text-2xl font-bold tracking-tight mb-6">
                        {{ $session->quiz->questions[$session->current_question_index]?->text ?? 'No Question' }}
                    </h2>

                    @if($session->show_answer)
                        {{-- Answer Revealed --}}
                        <div class="space-y-3">
                            @if($session->quiz->questions[$session->current_question_index])
                                @foreach($session->quiz->questions[$session->current_question_index]->answers as $answer)
                                    <div class="w-full p-4 rounded-xl border-2 {{ $answer->is_correct ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-gray-200 dark:border-gray-700 opacity-50' }}">
                                        <div class="flex items-center justify-between">
                                            <p class="{{ $answer->is_correct ? 'text-green-700 dark:text-green-400 font-semibold' : '' }}">
                                                {{ $answer->text }}
                                            </p>
                                            @if($answer->is_correct)
                                                <x-mary-icon name="o-check-circle" class="w-5 h-5 text-green-500" />
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="text-center mt-6">
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <x-mary-icon name="o-arrow-path" class="w-4 h-4 animate-spin text-gray-400" />
                                <p class="text-sm text-gray-500 dark:text-gray-400">Waiting for host to continue...</p>
                            </div>
                        </div>
                    @elseif(!$submitted)
                        {{-- Answer Selection --}}
                        <div class="space-y-3">
                            @if($session->quiz->questions[$session->current_question_index])
                                @foreach($session->quiz->questions[$session->current_question_index]->answers as $answer)
                                    <button
                                        wire:click="submitAnswer({{ $answer->id }})"
                                        type="button"
                                        class="w-full text-left p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all group"
                                    >
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/40 flex items-center justify-center text-sm font-semibold text-gray-500 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                                {{ $loop->index + 1 }}
                                            </div>
                                            <p class="font-medium">{{ $answer->text }}</p>
                                        </div>
                                    </button>
                                @endforeach
                            @endif
                        </div>
                    @else
                        {{-- Submitted --}}
                        <div class="text-center py-8">
                            <div class="mx-auto w-16 h-16 bg-gradient-to-br from-emerald-100 to-green-100 dark:from-emerald-900/40 dark:to-green-900/40 rounded-2xl flex items-center justify-center mb-4">
                                <x-mary-icon name="o-check" class="w-8 h-8 text-green-500" />
                            </div>
                            <p class="text-lg text-green-600 dark:text-green-400 font-semibold">Answer submitted!</p>
                            <p class="text-sm mt-1 text-gray-500 dark:text-gray-400">Waiting for host to continue...</p>
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
                <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">Thanks for playing! The host will share the results.</p>
                <x-mary-button class="btn-primary shadow-lg shadow-indigo-500/25" :link="route('dashboard')" icon="o-home">Back to Dashboard</x-mary-button>
            </div>
        @endif
    </div>
</div>
