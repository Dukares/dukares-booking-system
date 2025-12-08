<?php

namespace App\Services;

use App\Models\FraudEvent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

class FraudService
{
    /**
     * Calcola il punteggio antifrode (0–100)
     */
    public function calculateScore($action, $userId = null)
    {
        $ip = request()->ip();
        $ua = request()->userAgent();

        $score = 0;
        $details = [];

        /* -----------------------------------------------------
         * 1) IP su VPN / Proxy / Tor
         * ----------------------------------------------------- */
        if ($this->isVpnOrProxy($ip)) {
            $score += 25;
            $details['vpn_proxy'] = true;
        }

        /* -----------------------------------------------------
         * 2) Email sospette (usa nomi casuali)
         * ----------------------------------------------------- */
        if ($userId) {
            $user = \App\Models\User::find($userId);
            if ($user && $this->isSuspiciousEmail($user->email)) {
                $score += 20;
                $details['email_sospetta'] = $user->email;
            }
        }

        /* -----------------------------------------------------
         * 3) Troppi tentativi falliti (login/OTP)
         * ----------------------------------------------------- */
        if ($userId && RateLimiter::tooManyAttempts("user:{$userId}:login", 5)) {
            $score += 30;
            $details['troppi_tentativi'] = true;
        }

        /* -----------------------------------------------------
         * 4) Dispositivo nuovo (mai visto prima)
         * ----------------------------------------------------- */
        if ($this->isNewDevice()) {
            $score += 10;
            $details['nuovo_device'] = true;
        }

        /* -----------------------------------------------------
         * 5) Geo mismatch IP ↔ telefono durante OTP
         * ----------------------------------------------------- */
        if ($phoneCountry = session('otp_phone_country')) {
            $ipCountry = $this->geoCountry($ip);

            if ($ipCountry && $phoneCountry && $ipCountry !== $phoneCountry) {
                $score += 20;
                $details['geo_mismatch'] = "$ipCountry vs $phoneCountry";
            }
        }

        /* -----------------------------------------------------
         * 6) Log dell'evento antifrode
         * ----------------------------------------------------- */
        FraudEvent::create([
            'user_id'     => $userId,
            'action'      => $action,
            'score'       => $score,
            'details'     => $details,
            'ip'          => $ip,
            'user_agent'  => $ua,
        ]);

        return $score;
    }

    /**
     * Controlla se IP è VPN / Proxy
     */
    private function isVpnOrProxy($ip)
    {
        try {
            $response = Http::get("https://vpnapi.io/api/{$ip}?key=YOUR_API_KEY");

            return ($response['security']['vpn'] ?? false)
                || ($response['security']['proxy'] ?? false)
                || ($response['security']['tor'] ?? false);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Verifica email sospetta
     */
    private function isSuspiciousEmail($email)
    {
        return preg_match('/[0-9]{5,}/', $email)            // esempio: mario990283@gmail
            || str_contains($email, 'mailinator')
            || str_contains($email, 'tempmail')
            || str_contains($email, 'trash');
    }

    /**
     * Ritorna il paese dell'IP
     */
    private function geoCountry($ip)
    {
        try {
            $data = Http::get("https://ipapi.co/{$ip}/json");
            return $data['country'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Controllo se è un nuovo dispositivo
     */
    private function isNewDevice()
    {
        return !request()->cookies->has('device_id');
    }
}

