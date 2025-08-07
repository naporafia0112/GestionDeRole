<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if (!$user->active) {
            Auth::logout();
            return redirect('/login')->withErrors([
                'email' => 'Votre compte est désactivé. Contactez un administrateur.',
            ]);
        }

         if ($user->must_change_password) {
            return redirect()->route('password.change.form');
        }
        $redirectRoutes = [
            'ADMIN' => 'dashboard',
            'RH' => 'dashboard.RH',
            'DIRECTEUR' => 'dashboard.directeur',
            'TUTEUR' => 'dashboard.tuteur',
        ];

        foreach ($redirectRoutes as $role => $route) {
            if ($user->hasRole($role)) {
                return redirect()->route($route);
            }
        }


        return redirect()->intended(route('login', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
