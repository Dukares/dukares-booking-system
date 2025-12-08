<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Se l'utente è già loggato → mandalo alla dashboard
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        // Altrimenti lascia continuare
        return $next($request);
    }
}
