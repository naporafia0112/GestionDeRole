<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordChangeController extends Controller
{
    public function edit()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->save();

        // Redirections selon les rôles
        $redirectRoutes = [
            'ADMIN' => 'dashboard',
            'RH' => 'dashboard.RH',
            'DIRECTEUR' => 'dashboard.directeur',
            'TUTEUR' => 'dashboard.tuteur',
        ];

        foreach ($redirectRoutes as $role => $route) {
            if ($user->hasRole($role)) {
                return redirect()->route($route)->with('status', 'Mot de passe changé avec succès !');
            }
        }

        // Redirection par défaut si aucun rôle
        return redirect()->route('login')->with('status', 'Mot de passe changé avec succès !');
    }

}
