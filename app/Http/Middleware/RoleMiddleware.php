<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Gestisce l’accesso in base al ruolo utente.
     *
     * Uso:
     * middleware('role:admin')
     * middleware('role:owner,host')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();

        if (! $user) {
            abort(401, 'Non autenticato');
        }

        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Accesso non autorizzato');
        }

        return $next($request);
    }
}
