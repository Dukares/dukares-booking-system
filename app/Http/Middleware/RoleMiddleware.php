<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Gestisce l'autorizzazione basata sui ruoli.
     * Esempio uso: ->middleware('role:admin')
     *              ->middleware('role:admin,host')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Non autenticato.');
        }

        // Se il ruolo dell'utente è uno di quelli richiesti → OK
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Altrimenti blocchiamo l'accesso
        abort(403, 'Accesso negato. Ruolo non autorizzato.');
    }
}

