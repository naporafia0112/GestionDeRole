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
        
         if ($user->must_change_password) {
            return redirect()->route('password.change.form');
        }
        if ($user->hasRole('ADMIN')) {
        return redirect()->route('dashboard');
        }

        if ($user->hasRole('RH')) {
            return redirect()->route('dashboard.RH');
        }

        if ($user->hasRole('DIRECTEUR')) {
            return redirect()->route('dashboard.directeur');
        }
        if ($user->hasRole('TUTEUR')) {
            return redirect()->route('dashboard.tuteur');
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
