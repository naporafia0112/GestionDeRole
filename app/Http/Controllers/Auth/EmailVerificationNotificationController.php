<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            $user = $request->user();
            $role = strtolower($user->roles->first()->name ?? 'inconnu');

            switch ($role) {
                case 'admin':
                    return redirect()->route('dashboard'); // ou dashboard.admin si tu l’as défini ainsi
                case 'rh':
                    return redirect()->route('dashboard.RH');
                case 'directeur':
                    return redirect()->route('dashboard.directeur');
                case 'tuteur':
                    return redirect()->route('dashboard.tuteur');
                default:
                    return redirect()->route('home');
            }

        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
