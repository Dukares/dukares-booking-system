<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SmartFirewall
{
    public function handle(Request $request, Closure $next)
    {
        /*
        |--------------------------------------------------------------------------
        | 🔓 BYPASS TOTALE PER UTENTI AUTENTICATI
        |--------------------------------------------------------------------------
        | Se l’utente è loggato → MAI bloccare dashboard / backend
        */
        if (auth()->check()) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | 🔐 PROTEZIONE SOLO PER ROTTE PUBBLICHE SENSIBILI
        |--------------------------------------------------------------------------
        */
        $path = $request->path();

        $protectedPaths = [
            'login',
            'register',
            'password',
            'api',
        ];

        foreach ($protectedPaths as $protected) {
            if (str_starts_with($path, $protected)) {

                // Rate limit: 15 richieste / 60 sec
                $key = 'fw:' . $request->ip();
                $hits = Cache::get($key, 0) + 1;
                Cache::put($key, $hits, 60);

                if ($hits > 15) {
                    abort(429, 'Troppi tentativi. Riprova più tardi.');
                }

                // Pattern veramente pericolosi
                $badPatterns = [
                    '/\bunion\b/i',
                    '/\bselect\b.*\bfrom\b/i',
                    '/<script/i',
                    '/drop\s+table/i',
                ];

                foreach ($badPatterns as $pattern) {
                    if (preg_match($pattern, $request->fullUrl())) {
                        abort(403, 'Richiesta bloccata dal firewall');
                    }
                }
            }
        }

        return $next($request);
    }
}
