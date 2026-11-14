<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the social provider authentication page.
     */
    public function redirect(string $provider): RedirectResponse
    {
        if (request()->has('session_token')) {
            session(['intended_session_token' => request()->input('session_token')]);
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the social provider.
     */
    public function callback(string $provider): RedirectResponse
    {
        $socialUser = Socialite::driver($provider)->user();

        $email = $socialUser->getEmail();

        if ($email === null) {
            return to_route('login')
                ->with('error', 'Unable to retrieve your email from ' . ucfirst($provider) . '. Please try again or use email/password login.');
        }

        $user = User::query()->firstOrCreate(['email' => $email], [
            'name'     => $socialUser->getName() ?? $email,
            'password' => Hash::make(Str::random(32)),
        ]);

        Auth::login($user);

        $intendedToken = session()->pull('intended_session_token');

        if ($intendedToken) {
            return redirect()->to(route('session.join', ['token' => $intendedToken]));
        }

        return redirect()->to(route('dashboard'));
    }
}
