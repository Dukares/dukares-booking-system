<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountLockedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AccountLockService
{
    /**
     * 🔓 Metodo pubblico per permettere il test da Tinker
     */
    public function forceLock($user, $ip, $ua, $reasons = [])
    {
        return $this->lockAccount($user, $ip, $ua, $reasons);
    }

    /**
     * Analizza attività sospette e blocca l'account se necessario.
     */
    public function analyze($user): array
    {
        $ip  = Request::ip();
        $ua  = Request::userAgent();
        $now = now();

        $reasons = [];
        $risk = 0;

        // 1️⃣ Controllo login simultanei da IP diversi
        $lastIp = $user->last_ip ?? null;

        if ($lastIp && $lastIp !== $ip) {
            $risk += 40;
            $reasons[] = "ip_cambiato";
        }

        // 2️⃣ Cambio paese improvviso (richiede IpRiskService)
        if (session()->has('ip_country') && session('ip_country') !== session('session_ip_country')) {
            $risk += 50;
            $reasons[] = "cambio_paese_impossibile";
        }

        // 3️⃣ Browser diverso nella stessa sessione
        if ($user->last_user_agent && $user->last_user_agent !== $ua) {
            $risk += 30;
            $reasons[] = "browser_cambiato";
        }

        // 4️⃣ IP con reputazione molto bassa
        if (session()->has('ip_reputation') && session('ip_reputation') < 20) {
            $risk += 40;
            $reasons[] = "ip_reputazione_bassa";
        }

        // 5️⃣ Se l'utente è già segnato come "rischioso"
        if ($user->is_flagged) {
            $risk += 50;
            $reasons[] = "utente_gia_flaggato";
        }

        // Limita max 100
        $risk = min($risk, 100);

        // SE RISCHIO ALTO → BLOCCO
        if ($risk >= 80) {
            $this->lockAccount($user, $ip, $ua, $reasons);
            return [
                'locked' => true,
                'risk' => $risk,
                'reasons' => $reasons
            ];
        }

        // Salva dati normali
        $user->update([
            'last_ip' => $ip,
            'last_user_agent' => $ua,
            'is_flagged' => false
        ]);

        return [
            'locked' => false,
            'risk' => $risk,
            'reasons' => $reasons
        ];
    }

    /**
     * 🔐 Esegue il blocco completo dell'account.
     */
    private function lockAccount($user, $ip, $ua, $reasons)
    {
        $user->update([
            'is_flagged' => true,
            'locked_at' => now(),
            'lock_reason' => json_encode($reasons)
        ]);

        // Salviamo ID per lo sblocco tramite email
        session(['locked_user_id' => $user->id]);

        // Logout immediato
        Auth::logout();
        session()->invalidate();

        // Email di allerta
        Mail::to($user->email)->send(new AccountLockedMail($ip, $ua, $reasons));
    }

    /**
     * Sblocca l'account.
     */
    public function unlock($user)
    {
        $user->update([
            'is_flagged' => false,
            'locked_at' => null,
            'lock_reason' => null
        ]);
    }
}
