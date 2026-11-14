<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-indigo-50/30 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/20">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-indigo-600 focus:text-white focus:rounded-md">
        Skip to main content
    </a>

    <div id="main-content" class="px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        {{-- Header Section --}}
        <div class="mb-8 sm:mb-10">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">My Quizzes</h2>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">Manage and host your live quiz sessions</p>
                </div>
                <x-mary-button wire:click="createQuiz" icon="o-plus" class="btn-primary shrink-0 shadow-lg shadow-indigo-500/25">Create Quiz</x-mary-button>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8">
            <x-mary-card class="bg-gradient-to-br from-indigo-500 to-indigo-600 dark:from-indigo-500 dark:to-indigo-700 text-white border-none shadow-lg shadow-indigo-500/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <x-mary-icon name="o-document-text" class="w-5 h-5" />
                    </div>
                    <div>
                        <p class="text-xs text-indigo-100">Total Quizzes</p>
                        <p class="text-2xl font-bold">{{ \App\Models\Quiz::where('created_by', auth()->id())->count() }}</p>
                    </div>
                </div>
            </x-mary-card>
            <x-mary-card class="bg-gradient-to-br from-emerald-500 to-emerald-600 dark:from-emerald-500 dark:to-emerald-700 text-white border-none shadow-lg shadow-emerald-500/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <x-mary-icon name="o-question-mark-circle" class="w-5 h-5" />
                    </div>
                    <div>
                        <p class="text-xs text-emerald-100">Questions</p>
                        <p class="text-2xl font-bold">{{ \App\Models\Question::whereHas('quiz', fn($q) => $q->where('created_by', auth()->id()))->count() }}</p>
                    </div>
                </div>
            </x-mary-card>
            <x-mary-card class="col-span-2 sm:col-span-1 bg-gradient-to-br from-violet-500 to-purple-600 dark:from-violet-500 dark:to-purple-700 text-white border-none shadow-lg shadow-violet-500/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <x-mary-icon name="o-play" class="w-5 h-5" />
                    </div>
                    <div>
                        <p class="text-xs text-violet-100">Sessions</p>
                        <p class="text-2xl font-bold">{{ \App\Models\QuizSession::whereHas('quiz', fn($q) => $q->where('created_by', auth()->id()))->count() }}</p>
                    </div>
                </div>
            </x-mary-card>
        </div>

        {{-- Search & Filters --}}
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <x-mary-input wire:model.live="search" placeholder="Search quizzes..." class="w-full sm:max-w-md" icon="o-magnifying-glass" />
        </div>

        @if($this->quizzes()->count() > 0)
            {{-- Quiz Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
                @foreach($this->quizzes() as $quiz)
                    <a href="{{ route('quiz.edit', $quiz) }}" class="block group w-full hover:no-underline">
                        <x-mary-card class="w-full group hover:shadow-lg hover:shadow-indigo-500/5 transition-all duration-300 hover:-translate-y-0.5 cursor-pointer">
                            <div class="flex items-start justify-between mb-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center group-hover:bg-indigo-500 group-hover:text-white dark:group-hover:bg-indigo-500 transition-colors">
                                    <x-mary-icon name="o-document-text" class="w-5 h-5 text-indigo-600 dark:text-indigo-400 group-hover:text-white transition-colors" />
                                </div>
                                <x-mary-badge class="badge-sm">{{ $quiz->questions_count }} questions</x-mary-badge>
                            </div>
                            <h3 class="text-base font-semibold mb-1 line-clamp-1">{{ $quiz->title }}</h3>
                            @if($quiz->description)
                                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-3">{{ $quiz->description }}</p>
                            @endif
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-800">
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $quiz->created_at->diffForHumans() }}</p>
                                <div class="flex items-center gap-1">
                                    <x-mary-button class="btn-ghost btn-sm !p-1.5" :link="route('quiz.host', $quiz)" icon="o-play">Host</x-mary-button>
                                    <x-mary-button class="btn-error btn-sm !p-1.5" wire:click="deleteQuiz({{ $quiz->id }})" wire:confirm="Are you sure you want to delete this quiz?" icon="o-trash">Delete</x-mary-button>
                                </div>
                            </div>
                        </x-mary-card>
                    </a>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $this->quizzes()->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-16 sm:py-20">
                <div class="mx-auto w-20 h-20 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/40 dark:to-purple-900/40 rounded-2xl flex items-center justify-center mb-6">
                    <x-mary-icon name="o-document-text" class="w-10 h-10 text-indigo-500" />
                </div>
                <h3 class="text-lg font-semibold mb-2">No quizzes yet</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">Create your first quiz and start hosting live sessions with your audience.</p>
                <x-mary-button class="btn-primary shadow-lg shadow-indigo-500/25" wire:click="createQuiz" icon="o-plus">Create Your First Quiz</x-mary-button>
            </div>
        @endif
    </div>
</div>
