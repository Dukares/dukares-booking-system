<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class IpRiskService
{
    public function analyze($ip)
    {
        $risk = 0;
        $flags = [];

        if ($ip === null) {
            return [
                'risk' => 90,
                'reason' => 'IP nullo — possibile mascheramento.',
                'flags' => ['ip_null']
            ];
        }

        // ─────────────────────────────────────────────
        // 1) Controllo VPN / Proxy (API gratuita ipapi.co)
        // ─────────────────────────────────────────────
        try {
            $data = Http::get("https://ipapi.co/{$ip}/json/")->json();

            if (!empty($data['proxy']) && $data['proxy'] === true) {
                $risk += 40;
                $flags[] = 'proxy_detected';
            }

            if (!empty($data['vpn']) && $data['vpn'] === true) {
                $risk += 50;
                $flags[] = 'vpn_detected';
            }

            // ─────────────────────────────────────────────
            // 2) Se l’IP proviene da un paese diverso
            // ─────────────────────────────────────────────
            $currentCountry = $data['country'] ?? null;
            $lastCountry = session('last_login_country');

            if ($lastCountry && $currentCountry && $lastCountry !== $currentCountry) {
                $risk += 30;
                $flags[] = 'country_changed';
            }

            // Salva paese corrente in sessione
            session(['last_login_country' => $currentCountry]);

        } catch (\Exception $e) {
            $risk += 20;
            $flags[] = 'ip_check_error';
        }

        // ─────────────────────────────────────────────
        // 3) Se rischio troppo basso, lo limitiamo a 0
        // ─────────────────────────────────────────────
        if ($risk < 0) $risk = 0;
        if ($risk > 100) $risk = 100;

        return [
            'risk' => $risk,
            'flags' => $flags
        ];
    }
}
