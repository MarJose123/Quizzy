<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-indigo-50/30 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/20">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-indigo-600 focus:text-white focus:rounded-md">
        Skip to main content
    </a>

    <div id="main-content" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        {{-- Breadcrumb & Header --}}
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-4">
                <x-mary-button class="btn-ghost btn-sm !p-1 !text-gray-500" :link="route('dashboard')">Dashboard</x-mary-button>
                <x-mary-icon name="o-chevron-right" class="w-4 h-4" />
                <span class="text-gray-900 dark:text-gray-100">{{ isset($quiz) ? 'Edit Quiz' : 'New Quiz' }}</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight">{{ isset($quiz) ? 'Edit Quiz' : 'Create a New Quiz' }}</h1>
            <p class="mt-1 text-gray-500 dark:text-gray-400">Build engaging questions for your live session</p>
        </div>

        <form wire:submit="save" class="space-y-8">
            {{-- Quiz Details Card --}}
            <x-mary-card class="shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-9 h-9 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                        <x-mary-icon name="o-document-text" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <h3 class="text-base font-semibold">Quiz Details</h3>
                </div>

                <div class="space-y-5">
                    <x-mary-input label="Title" id="title" wire:model="title" placeholder="e.g., JavaScript Fundamentals" icon="o-document-text" />

                    <x-mary-textarea label="Description" id="description" wire:model="description" rows="3" placeholder="Brief description of your quiz..." />
                </div>
            </x-mary-card>

            {{-- Questions Section --}}
            <div class="space-y-6">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
                            <x-mary-icon name="o-question-mark-circle" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Questions</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ count($questions) }} question{{ count($questions) !== 1 ? 's' : '' }} added</p>
                        </div>
                    </div>
                    <x-mary-button type="button" wire:click="openAddQuestionDialog" icon="o-plus" class="btn-primary btn-sm shadow-md shadow-indigo-500/20">Add Question</x-mary-button>
                </div>

                @error('questions') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

                @empty($questions)
                    <div class="text-center py-16 bg-white dark:bg-gray-900 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-800">
                        <div class="mx-auto w-16 h-16 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/40 dark:to-purple-900/40 rounded-2xl flex items-center justify-center mb-4">
                            <x-mary-icon name="o-question-mark-circle" class="w-8 h-8 text-indigo-500" />
                        </div>
                        <h4 class="text-base font-semibold mb-2">No questions yet</h4>
                        <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-5">Add your first question to get started. You can create multiple choice or true/false questions.</p>
                        <x-mary-button type="button" wire:click="openAddQuestionDialog" icon="o-plus" class="btn-primary shadow-md shadow-indigo-500/20">Add Your First Question</x-mary-button>
                    </div>
                @endempty

                {{-- Questions List --}}
                @foreach($questions as $index => $question)
                    <div wire:key="question-{{ $index }}" class="flex items-center gap-4 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-800 p-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold line-clamp-1">{{ $question['text'] }}</h4>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                                {{ $question['type'] === 'true_false' ? 'True/False' : 'Choices' }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                <x-mary-icon name="o-star" class="w-3 h-3" />
                                {{ $question['points'] ?? 10 }} pts
                            </span>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <x-mary-button type="button" wire:click="openEditQuestionDialog({{ $index }})" icon="o-pencil" class="btn-ghost btn-sm text-gray-500 hover:text-indigo-600">Edit</x-mary-button>
                            <x-mary-button type="button" wire:click="confirmDeleteQuestion({{ $index }})" icon="o-trash" class="btn-ghost btn-sm text-gray-500 hover:text-red-600">Remove</x-mary-button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                <x-mary-button type="submit" icon="o-document-check" wire:loading.attr="disabled" wire:target="save" class="btn-primary shadow-lg shadow-indigo-500/25 sm:w-auto w-full">
                    <span wire:loading.remove wire:target="save">Save Quiz</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </x-mary-button>
                <x-mary-button type="button" wire:click="cancel" icon="o-x-mark" class="btn-ghost sm:w-auto w-full">Cancel</x-mary-button>
            </div>
        </form>
    </div>

    {{-- Question Dialog Modal --}}
    <x-mary-modal wire:model="showQuestionDialog" :title="$editingQuestionIndex !== null ? 'Edit Question' : 'Add Question'" :subtitle="$editingQuestionIndex !== null ? 'Update the question details below.' : 'Create a new question for your quiz.'">

        <form wire:submit="saveQuestion" class="space-y-5">
            {{-- Question Text --}}
            <x-mary-textarea label="Question Text" wire:model="dialogQuestionText" rows="3" placeholder="Type your question here..." icon="o-question-mark-circle" />

            {{-- Question Type --}}
            <div>
                <label class="block text-sm font-medium mb-2">Question Type</label>
                <div class="space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="dialog-type" value="multiple_choice" wire:model.live="dialogQuestionType" class="radio radio-primary radio-sm" />
                        <span class="text-sm">Choices</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="dialog-type" value="true_false" wire:model.live="dialogQuestionType" class="radio radio-primary radio-sm" />
                        <span class="text-sm">True/False</span>
                    </label>
                </div>
            </div>

            {{-- Points --}}
            <x-mary-input label="Points (awarded for correct answer)" wire:model="dialogQuestionPoints" type="number" min="1" max="100" placeholder="10" icon="o-star" hint="Participants earn these points when they answer correctly." />

            {{-- Answers --}}
            <div>
                <label class="block text-sm font-medium mb-2">Answers (select the correct answer)</label>
                <div class="space-y-2">
                    @foreach($dialogAnswers as $aIndex => $answer)
                        <div wire:key="dialog-answer-{{ $aIndex }}" class="flex items-center gap-2 group/answer">
                            <input
                                type="radio"
                                id="dialog-correct-{{ $aIndex }}"
                                name="dialog-correct"
                                value="{{ $aIndex }}"
                                wire:model="dialogCorrectAnswerIndex"
                                class="radio radio-primary radio-sm"
                            />
                            <input
                                type="text"
                                wire:model="dialogAnswers.{{ $aIndex }}.text"
                                placeholder="Answer {{ $aIndex + 1 }}"
                                aria-labelledby="dialog-correct-{{ $aIndex }}"
                                class="input input-bordered input-sm w-full"
                            />
                            @if(count($dialogAnswers) > 2 && $dialogQuestionType !== 'true_false')
                                <x-mary-button type="button" wire:click="removeDialogAnswer({{ $aIndex }})" icon="o-x-mark" class="btn-ghost btn-xs text-gray-400 hover:text-red-500 opacity-0 group-hover/answer:opacity-100"></x-mary-button>
                            @endif
                        </div>
                        @error("dialogAnswers.$aIndex.text") <p class="text-red-500 text-sm ml-6">{{ $message }}</p> @enderror
                    @endforeach
                </div>
                @if($dialogQuestionType === 'multiple_choice')
                    <div class="mt-2">
                        <x-mary-button type="button" wire:click="addDialogAnswer" icon="o-plus" class="btn-ghost btn-sm text-sm text-indigo-600">Add another answer</x-mary-button>
                    </div>
                @endif
            </div>

            {{-- Dialog Actions --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                <x-mary-button type="button" wire:click="closeQuestionDialog" icon="o-x-mark" class="btn-ghost">Cancel</x-mary-button>
                <x-mary-button type="submit" icon="o-check" wire:loading.attr="disabled" wire:target="saveQuestion" class="btn-primary shadow-md shadow-indigo-500/20">
                    <span wire:loading.remove wire:target="saveQuestion">{{ $editingQuestionIndex !== null ? 'Update Question' : 'Add Question' }}</span>
                    <span wire:loading wire:target="saveQuestion">Saving...</span>
                </x-mary-button>
            </div>
        </form>
    </x-mary-modal>

    {{-- Delete Confirmation Modal --}}
    <x-mary-modal wire:model="showDeleteConfirm" title="Delete Question" subtitle="Are you sure you want to delete this question? This action cannot be undone.">

        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/40 flex items-center justify-center flex-shrink-0">
                <x-mary-icon name="o-trash" class="w-6 h-6 text-red-600 dark:text-red-400" />
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <x-mary-button type="button" wire:click="closeDeleteConfirm" icon="o-x-mark" class="btn-ghost">Cancel</x-mary-button>
            <x-mary-button type="button" wire:click="confirmDelete" icon="o-trash" class="btn-error">Delete</x-mary-button>
        </div>
    </x-mary-modal>
</div>
