<x-layouts.app :title="'Login'">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md p-8 space-y-6 bg-white dark:bg-gray-900 rounded-lg shadow">
            <div class="text-center">
                <h1 class="text-2xl font-bold">Quizzy</h1>
                <p class="text-gray-600 dark:text-gray-400">Sign in to your account</p>
            </div>

            <form wire:submit="login" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input
                        type="email"
                        id="email"
                        wire:model="email"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input
                        type="password"
                        id="password"
                        wire:model="password"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">
                    Sign in
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
