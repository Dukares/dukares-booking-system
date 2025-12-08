<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SecurityCheck
{
    /**
     * Verifica sicurezza avanzata:
     * - Telefono verificato
     * - Ultimo login anomalo
     * - IP sospetto
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        /* -------------------------------------------------
         * 1) BLOCCO SE TELEFONO NON VERIFICATO (SMS OTP)
         * ------------------------------------------------- */
        if (is_null($user->phone_verified_at)) {
            return redirect()->route('security.phone.show')
                ->with('warning', 'Per motivi di sicurezza devi verificare il tuo numero di telefono.');
        }

        /* -------------------------------------------------
         * 2) BLOCCO SE L'IP È SOSPETTO (nuovo IP)
         * ------------------------------------------------- */
        $currentIp = $request->ip();

        if ($user->last_login_ip && $user->last_login_ip !== $currentIp) {
            // Potresti fare logica più complessa in futuro
            session()->put('security_suspect_ip', $currentIp);

            return redirect()->route('security.phone.show')
                ->with('warning', 'Nuovo IP rilevato. Per sicurezza conferma il tuo accesso.');
        }

        /* -------------------------------------------------
         * 3) TUTTO OK → Procedi
         * ------------------------------------------------- */
        return $next($request);
    }
}
