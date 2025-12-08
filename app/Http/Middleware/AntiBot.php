<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AntiBot
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();

        /* --------------------------------------------------
         | 1) BLOCCO BOT USER-AGENT (molti bot noti)
         -------------------------------------------------- */
        $badAgents = [
            'curl', 'python', 'wget', 'bot', 'crawler',
            'spider', 'scrapy', 'httpclient', 'java',
        ];

        $agent = strtolower($request->userAgent());

        foreach ($badAgents as $bad) {
            if (str_contains($agent, $bad)) {
                abort(403, 'Access denied (Bot detected).');
            }
        }

        /* --------------------------------------------------
         | 2) RATE LIMIT BASE IP
         |    Es: massimo 30 richieste ogni 30 secondi
         -------------------------------------------------- */
        $key = 'req_count_' . $ip;
        $count = Cache::get($key, 0) + 1;

        Cache::put($key, $count, now()->addSeconds(30));

        if ($count > 30) {
            abort(429, 'Too many requests. Slow down.');
        }

        /* --------------------------------------------------
         | 3) LOGIN PROTECTION
         |    massimo 5 tentativi ogni 2 minuti
         -------------------------------------------------- */
        if ($request->is('login')) {
            $loginKey = "login_attempts_" . $ip;
            $attempts = Cache::increment($loginKey, 1, now()->addMinutes(2));

            if ($attempts > 5) {
                abort(429, 'Too many login attempts. Try again later.');
            }
        }

        /* --------------------------------------------------
         | 4) SMS / PHONE VERIFICATION
         -------------------------------------------------- */
        if ($request->is('security/phone/send')) {
            $smsKey = "sms_attempts_" . $ip;
            $smsAttempts = Cache::increment($smsKey, 1, now()->addMinutes(10));

            if ($smsAttempts > 3) {
                abort(429, 'Too many SMS requests. Try again later.');
            }
        }

        return $next($request);
    }
}
