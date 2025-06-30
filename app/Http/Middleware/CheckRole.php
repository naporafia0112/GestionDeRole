<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Vérifie si l'utilisateur a un des rôles
        if (!$user->roles()->whereIn('name', $roles)->exists()) {
            abort(403, 'Accès interdit – Rôle insuffisant.');
        }

        return $next($request);
    }
}
