<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public function login(): void
    {
        $credentials = $this->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            session()->regenerate();
            redirect()->intended(route('dashboard'));

            return;
        }

        $this->addError('email', 'These credentials do not match our records.');
    }

    public function render(): View
    {
        return view('livewire.login');
    }
}
