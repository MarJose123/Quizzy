<x-layouts.app :title="($quiz ? 'Edit' : 'Create').' Quiz'">
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
            <form wire:submit="save" class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium">Title</label>
                    <input
                        type="text"
                        id="title"
                        wire:model="title"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                    >
                    @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium">Description</label>
                    <textarea
                        id="description"
                        wire:model="description"
                        rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                    ></textarea>
                    @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Questions</h3>
                        <button type="button" wire:click="addQuestion" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                            Add Question
                        </button>
                    </div>

                    @foreach($questions as $index => $question)
                        <div class="border rounded-lg p-4 dark:border-gray-700">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="font-medium">Question {{ $index + 1 }}</h4>
                                <button type="button" wire:click="removeQuestion({{ $index }})" class="text-red-600 text-sm">Remove</button>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <input
                                        type="text"
                                        wire:model="questions.{{ $index }}.text"
                                        placeholder="Question text"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                                    >
                                    @error("questions.$index.text") <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="flex space-x-4">
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            name="type-{{ $index }}"
                                            value="multiple_choice"
                                            wire:change="toggleQuestionType({{ $index }}, 'multiple_choice')"
                                            class="mr-2"
                                        >
                                        Multiple Choice
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            name="type-{{ $index }}"
                                            value="true_false"
                                            wire:change="toggleQuestionType({{ $index }}, 'true_false')"
                                            class="mr-2"
                                        >
                                        True/False
                                    </label>
                                </div>

                                <div class="space-y-2">
                                    @foreach($question['answers'] as $aIndex => $answer)
                                        <div class="flex items-center space-x-2">
                                            <input
                                                type="radio"
                                                name="correct-{{ $index }}"
                                                wire:change="$set('questions.{{ $index }}.answers.{{ $aIndex }}.is_correct', true)"
                                                class="mr-2"
                                            >
                                            <input
                                                type="text"
                                                wire:model="questions.{{ $index }}.answers.{{ $aIndex }}.text"
                                                placeholder="Answer {{ $aIndex + 1 }}"
                                                class="flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                                            >
                                            @error("questions.$index.answers.$aIndex.text") <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex space-x-4">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                        Save Quiz
                    </button>
                    <button type="button" wire:click="cancel" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 px-4 py-2 rounded-md">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
