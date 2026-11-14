<x-layouts.app :title="'Host Session'">
    <div class="min-h-screen">
        <nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold">Quizzy</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 dark:text-gray-400">Dashboard</a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if($session->status === 'waiting')
                <div class="text-center space-y-6">
                    <h2 class="text-2xl font-bold">Waiting for Participants</h2>
                    <p class="text-gray-600 dark:text-gray-400">{{ $this->participantCount() }} participant(s) joined</p>

                    <div class="space-y-2">
                        <p class="text-sm font-medium">Share this link:</p>
                        <div class="flex justify-center">
                            <input type="text" readonly value="{{ $this->inviteLink() }}"
                                   class="px-4 py-2 rounded-l-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 w-96">
                            <button wire:click="copyInviteLink" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r-md">
                                Copy
                            </button>
                        </div>
                    </div>

                    <button wire:click="startSession" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-md text-lg">
                        Start Session
                    </button>
                </div>
            @elseif($session->status === 'active')
                <div class="space-y-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-2xl font-bold">{{ $this->currentQuestion()?->text ?? 'No Question' }}</h2>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            Question {{ $session->current_question_index + 1 }} of {{ $this->totalQuestions() }}
                        </span>
                    </div>

                    @if($this->currentQuestion())
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($this->currentQuestion()->answers as $answer)
                                <div class="p-4 border rounded-lg dark:border-gray-700">
                                    {{ $answer->text }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="flex justify-center space-x-4">
                        @if(!$session->show_answer)
                            <button wire:click="revealAnswer" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                                Reveal Answer
                            </button>
                        @elseif(!$session->show_scores)
                            <button wire:click="showScores" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                                Show Scores
                            </button>
                        @endif

                        @if($session->current_question_index < $this->totalQuestions() - 1)
                            <button wire:click="nextQuestion" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                Next Question
                            </button>
                        @endif

                        @if($session->current_question_index > 0)
                            <button wire:click="previousQuestion" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 px-4 py-2 rounded-md">
                                Previous
                            </button>
                        @endif
                    </div>

                    <div class="text-center">
                        <button wire:click="endSession" class="text-red-600 hover:text-red-800">End Session</button>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <h2 class="text-2xl font-bold">Session Finished</h2>
                    <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800">Back to Dashboard</a>
                </div>
            @endif
        </div>

        @wire('poll')
    </div>
</x-layouts.app>
