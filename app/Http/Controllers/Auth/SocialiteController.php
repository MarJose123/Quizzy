<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the social provider authentication page.
     */
    public function redirect(string $provider): RedirectResponse
    {
        if (! in_array($provider, ['zoho', 'microsoft'], true)) {
            abort(404);
        }

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
        if (! in_array($provider, ['zoho', 'microsoft'], true)) {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Exception) {
            return to_route('login')
                ->with('error', 'Unable to connect to ' . ucfirst($provider) . '. Please try again or use email/password login.');
        }

        $email = $socialUser->getEmail();

        if ($email === null) {
            return to_route('login')
                ->with('error', 'Unable to retrieve your email from ' . ucfirst($provider) . '. Please try again or use email/password login.');
        }

        try {
            $user = User::query()->firstOrCreate(
                ['email' => $email],
                [
                    'name'     => $socialUser->getName() ?? $email,
                    'password' => Hash::make(Str::random(32)),
                    'role'     => UserRole::Participant,
                ]
            );
        } catch (UniqueConstraintViolationException) {
            // Race condition: another request created the user simultaneously
            $user = User::query()->where('email', $email)->firstOrFail();
        }

        Auth::login($user);

        $intendedToken = session()->pull('intended_session_token');

        if ($intendedToken) {
            return redirect()->to(route('session.join', ['token' => $intendedToken]));
        }

        return redirect()->to(route('dashboard'));
    }
}
