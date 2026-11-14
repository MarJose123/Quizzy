<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-indigo-50/30 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/20" wire:poll.2s="poll">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-indigo-600 focus:text-white focus:rounded-md">
        Skip to main content
    </a>

    <div id="main-content" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        @if($session->status === \App\Enums\SessionStatus::Waiting)
            {{-- Waiting Lobby --}}
            <div class="text-center space-y-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/40 dark:to-purple-900/40 rounded-2xl mb-2">
                    <x-mary-icon name="o-user-group" class="w-10 h-10 text-indigo-500" />
                </div>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight mb-2">Waiting for Participants</h2>
                    <p class="text-gray-500 dark:text-gray-400">Share the link and wait for your participants to join</p>
                </div>

                {{-- Participant Count Badge --}}
                <x-mary-card class="inline-flex items-center gap-3 px-6 py-4 shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
                        <x-mary-icon name="o-user" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div class="text-left">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Joined</p>
                        <p class="text-lg font-semibold">{{ $this->participantCount() }} participant{{ $this->participantCount() !== 1 ? 's' : '' }}</p>
                    </div>
                </x-mary-card>

                {{-- Invite Link --}}
                <div class="max-w-md mx-auto">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Share this link with your participants:</p>
                    <div class="flex gap-2">
                        <x-mary-input type="text" readonly :value="$this->inviteLink()" class="flex-1 rounded-r-none" icon="o-link" aria-label="Invite link" />
                        <x-mary-button wire:click="copyInviteLink" class="btn-primary rounded-l-none shrink-0" icon="o-clipboard" aria-label="Copy invite link">Copy</x-mary-button>
                    </div>
                </div>

                <x-mary-button wire:click="startSession" icon="o-play" wire:loading.attr="disabled" wire:target="startSession" class="btn-primary shadow-lg shadow-indigo-500/25 text-lg px-8">Start Session</x-mary-button>
            </div>
        @elseif($session->status === \App\Enums\SessionStatus::Active)
            {{-- Active Quiz --}}
            <div class="space-y-8">
                {{-- Progress Bar --}}
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <x-mary-badge>
                            Question {{ $session->current_question_index + 1 }} of {{ $this->totalQuestions() }}
                        </x-mary-badge>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ round((($session->current_question_index + 1) / $this->totalQuestions()) * 100) }}% complete</p>
                    </div>
                    <div class="w-full h-2 bg-gray-200 dark:bg-gray-800 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full transition-all duration-500" style="width: {{ (($session->current_question_index + 1) / $this->totalQuestions()) * 100 }}%"></div>
                    </div>
                </div>

                {{-- Question Card --}}
                <x-mary-card class="shadow-xl shadow-gray-900/5 dark:shadow-gray-900/30">
                    <h2 class="text-2xl font-bold tracking-tight mb-6">{{ $this->currentQuestion()?->text ?? 'No Question' }}</h2>

                    @if($this->currentQuestion())
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($this->currentQuestion()->answers as $answer)
                                <x-mary-card class="hover:shadow-md transition-shadow">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                                            {{ $loop->index + 1 }}
                                        </div>
                                        <p class="font-medium">{{ $answer->text }}</p>
                                    </div>
                                </x-mary-card>
                            @endforeach
                        </div>
                    @endif
                </x-mary-card>

                {{-- Controls --}}
                <div class="flex flex-col sm:flex-row justify-center gap-3">
                    @if(!$session->show_answer)
                        <x-mary-button wire:click="revealAnswer" icon="o-eye" wire:loading.attr="disabled" wire:target="revealAnswer" class="btn-primary shadow-md shadow-indigo-500/20">Reveal Answer</x-mary-button>
                    @elseif(!$session->show_scores)
                        <x-mary-button wire:click="showScores" icon="o-chart-bar" wire:loading.attr="disabled" wire:target="showScores" class="btn-primary shadow-md shadow-indigo-500/20">Show Scores</x-mary-button>
                    @endif

                    @if($session->current_question_index < $this->totalQuestions() - 1)
                        <x-mary-button wire:click="nextQuestion" icon="o-arrow-right" wire:loading.attr="disabled" wire:target="nextQuestion" class="btn-primary shadow-md shadow-indigo-500/20">Next Question</x-mary-button>
                    @endif

                    @if($session->current_question_index > 0)
                        <x-mary-button wire:click="previousQuestion" icon="o-arrow-left" class="btn-ghost">Previous</x-mary-button>
                    @endif
                </div>

                <div class="text-center pt-4 border-t border-gray-200 dark:border-gray-800">
                    <x-mary-button wire:click="endSession" icon="o-stop" class="btn-error">End Session</x-mary-button>
                </div>
            </div>
        @else
            {{-- Finished --}}
            <div class="text-center py-16 sm:py-20">
                <div class="mx-auto w-20 h-20 bg-gradient-to-br from-emerald-100 to-green-100 dark:from-emerald-900/40 dark:to-green-900/40 rounded-2xl flex items-center justify-center mb-6">
                    <x-mary-icon name="o-check" class="w-10 h-10 text-emerald-500" />
                </div>
                <h2 class="text-2xl font-bold tracking-tight mb-2">Session Finished</h2>
                <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">Great job hosting! Your participants can now see the results.</p>
                <x-mary-button class="btn-primary shadow-lg shadow-indigo-500/25" :link="route('dashboard')" icon="o-home">Back to Dashboard</x-mary-button>
            </div>
        @endif
    </div>

    <div x-data x-init="Livewire.find('{{ $__livewire_id ?? '' }}').$on('invite-link-copied', ({ link }) => {
        navigator.clipboard.writeText(link).then(() => {
            Livewire.dispatch('toast', { message: 'Link copied to clipboard!', type: 'success' });
        });
    })"></div>
</div>
