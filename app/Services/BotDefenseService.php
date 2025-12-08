<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class BotDefenseService
{
    /**
     * Analizza un tentativo di login e ritorna:
     * - livello di rischio (0–100)
     * - motivi (array)
     * - azione raccomandata
     */
    public function analyze(string $email): array
    {
        $ip = Request::ip();
        $userAgent = Request::header('User-Agent');

        $reasons = [];
        $risk = 0;

        // 1) USER AGENT sospetto (bot o curl)
        if (!$userAgent || strlen($userAgent) < 10) {
            $risk += 40;
            $reasons[] = "user_agent_sospetto";
        }

        // 2) Molti tentativi dallo stesso IP
        $last10min = DB::table('failed_logins')
            ->where('ip', $ip)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->count();

        if ($last10min >= 5) {
            $risk += 30;
            $reasons[] = "troppi_tentativi_da_stesso_ip";
        }

        // 3) Tentativi rapidissimi (bot)
        $lastAttempts = DB::table('failed_logins')
            ->where('ip', $ip)
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        if ($lastAttempts->count() >= 2) {
            $t1 = $lastAttempts[0]->created_at ?? null;
            $t2 = $lastAttempts[1]->created_at ?? null;

            if ($t1 && $t2 && $t1->diffInSeconds($t2) <= 2) {
                $risk += 35;
                $reasons[] = "tentativi_troppo_veloci_bot";
            }
        }

        // 4) Pattern sospetti nel browser
        if (preg_match('/Headless|Phantom|Scraper|Bot/i', $userAgent)) {
            $risk += 40;
            $reasons[] = "browser_headless";
        }

        // 5) Email non registrata → spesso brute force
        $emailExists = DB::table('users')->where('email', $email)->exists();
        if (!$emailExists) {
            $risk += 10;
            $reasons[] = "email_non_registrata";
        }

        // Limiti di sicurezza
        $risk = min($risk, 100);

        // Azione consigliata
        $action = "allow";

        if ($risk >= 80) {
            $action = "block";
        } elseif ($risk >= 50) {
            $action = "otp"; // rischio medio-alto → OTP
        }

        return [
            "ip" => $ip,
            "risk" => $risk,
            "reasons" => $reasons,
            "action" => $action
        ];
    }


    /**
     * Registra un tentativo fallito
     */
    public function logFailed(string $email): void
    {
        DB::table('failed_logins')->insert([
            'email' => $email,
            'ip' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
            'created_at' => now(),
        ]);
    }
}
