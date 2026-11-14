<x-layouts.app :title="'Dashboard'">
    <div class="min-h-screen">
        <nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold">Quizzy</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">My Quizzes</h2>
                <button wire:click="createQuiz" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                    Create Quiz
                </button>
            </div>

            <div class="mb-4">
                <input
                    type="text"
                    wire:model.live="search"
                    placeholder="Search quizzes..."
                    class="w-full max-w-md px-4 py-2 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                >
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Questions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach($this->quizzes() as $quiz)
                            <tr>
                                <td class="px-6 py-4">{{ $quiz->title }}</td>
                                <td class="px-6 py-4">{{ $quiz->questions_count }}</td>
                                <td class="px-6 py-4">{{ $quiz->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 space-x-2">
                                    <a href="{{ route('quiz.edit', $quiz) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                                    <a href="{{ route('quiz.host', $quiz) }}" class="text-green-600 hover:text-green-800">Host</a>
                                    <button wire:click="deleteQuiz({{ $quiz->id }})" class="text-red-600 hover:text-red-800">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $this->quizzes()->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
