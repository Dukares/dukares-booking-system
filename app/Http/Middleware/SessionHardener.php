<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AgentService;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewDeviceAlertMail;

class SessionHardener
{
    public function handle(Request $request, Closure $next)
    {
        // Utente NON loggato → non serve verificare
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $agent = new AgentService();

        $currentIp       = $request->ip();
        $currentBrowser  = $agent->browser();
        $currentOs       = $agent->os();
        $currentDevice   = $agent->deviceType();

        // Fingerprint attuale
        $currentFp = sha1($currentIp . $currentBrowser . $currentOs . $currentDevice);

        // --- SE E' LA PRIMA REQUEST DELLA SESSIONE ---
        if (!session()->has('session_fp')) {

            session([
                'session_fp'  => $currentFp,
                'session_ip'  => $currentIp,
                'session_ua'  => $request->userAgent(),
            ]);

            return $next($request);
        }

        // --- CONFRONTO FINGERPRINT SESSIONE ---
        $sessionFp = session('session_fp');
        $sessionIp = session('session_ip');
        $sessionUa = session('session_ua');

        // Se fingerprint cambia → ATTACCO
        if ($sessionFp !== $currentFp) {

            // Invia email di allerta
            Mail::to($user->email)->send(
                new NewDeviceAlertMail(
                    $currentIp,
                    $currentBrowser,
                    $currentOs,
                    $currentDevice
                )
            );

            // Logout immediato e blocco sessione
            Auth::logout();
            session()->invalidate();

            return redirect('/login')->withErrors([
                'error' => 'La tua sessione è stata chiusa per attività sospetta.'
            ]);
        }

        // Cambio IP sospetto (rotazione VPN durante la sessione)
        if ($sessionIp !== $currentIp) {

            Mail::to($user->email)->send(
                new NewDeviceAlertMail(
                    $currentIp,
                    $currentBrowser,
                    $currentOs,
                    $currentDevice
                )
            );

            Auth::logout();
            session()->invalidate();

            return redirect('/login')->withErrors([
                'error' => 'Sessione terminata per cambio IP anomalo.'
            ]);
        }

        // Cambio User-Agent (browser spoofing)
        if ($sessionUa !== $request->userAgent()) {

            Mail::to($user->email)->send(
                new NewDeviceAlertMail(
                    $currentIp,
                    $currentBrowser,
                    $currentOs,
                    $currentDevice
                )
            );

            Auth::logout();
            session()->invalidate();

            return redirect('/login')->withErrors([
                'error' => 'Sessione terminata: possibile manomissione del browser.'
            ]);
        }

        return $next($request);
    }
}
