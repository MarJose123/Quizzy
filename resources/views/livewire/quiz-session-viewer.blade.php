<x-layouts.app :title="'View Session'">
    <div class="min-h-screen">
        <nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold">Quizzy</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Viewer</span>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if($session->status === 'waiting')
                <div class="text-center py-12">
                    <h2 class="text-2xl font-bold">Waiting for Host to Start</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Please wait...</p>
                </div>
            @elseif($session->status === 'active')
                <div class="space-y-6">
                    <h2 class="text-2xl font-bold">{{ $session->quiz->questions[$session->current_question_index]?->text ?? 'No Question' }}</h2>

                    @if($session->show_answer && $session->quiz->questions[$session->current_question_index])
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($session->quiz->questions[$session->current_question_index]->answers as $answer)
                                <div class="p-4 border rounded-lg {{ $answer->is_correct ? 'bg-green-100 border-green-500 dark:bg-green-900' : 'dark:border-gray-700' }}">
                                    {{ $answer->text }}
                                    @if($answer->is_correct)
                                        <span class="text-green-600 font-bold">✓ Correct</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-12">
                    <h2 class="text-2xl font-bold">Session Finished</h2>
                    <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800">Back to Dashboard</a>
                </div>
            @endif
        </div>

        @wire('poll')
    </div>
</x-layouts.app>
