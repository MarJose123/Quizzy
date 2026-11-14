import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

// Dark mode handling — matches Flux behavior
// Note: persist plugin is included by default in Livewire 4's bundled Alpine
Alpine.data('appearance', () => ({
    appearance: Alpine.$persist('light').as('appearance'),

    init() {
        // Initialize from localStorage or system preference
        if (!localStorage.getItem('appearance')) {
            this.appearance = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        this.$watch('appearance', () => this.update());
        this.update();
    },

    update() {
        document.documentElement.classList.toggle('dark', this.appearance === 'dark');
    },
}));

Livewire.start();
