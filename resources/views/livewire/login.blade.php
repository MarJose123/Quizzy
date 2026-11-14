<x-layouts.app :title="'Login'">
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-gray-50 via-white to-indigo-50/30 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/20">
        <div class="w-full max-w-md px-4">
            {{-- Brand Header --}}
            <div class="text-center mb-8">
                <div class="mb-5">
                    <x-app-logo size="md" />
                </div>
                <h1 class="text-2xl font-bold tracking-tight mb-2">Welcome back</h1>
                <p class="text-gray-500 dark:text-gray-400">Sign in to your Quizzy account</p>
            </div>

            {{-- Login Card --}}
            <x-mary-card class="shadow-xl shadow-gray-900/5 dark:shadow-gray-900/30">
                <form wire:submit="login" class="space-y-5">
                    <x-mary-input label="Email" type="email" id="email" wire:model="email" required placeholder="you@example.com" icon="o-envelope" />

                    <x-mary-input label="Password" type="password" id="password" wire:model="password" required icon="o-lock-closed" />

                    <x-mary-button type="submit" class="btn-primary w-full shadow-lg shadow-indigo-500/25">Sign in</x-mary-button>
                </form>
            </x-mary-card>

            {{-- Footer --}}
            <p class="text-center mt-6 text-sm text-gray-400 dark:text-gray-500">
                Sign in to create and host live quizzes
            </p>
        </div>
    </div>
</x-layouts.app>
