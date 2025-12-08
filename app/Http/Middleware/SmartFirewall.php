<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class SmartFirewall
{
    public function handle(Request $request, Closure $next)
    {
        $ip      = $request->ip();
        $path    = $request->path();
        $now     = now();

        // Applichiamo il firewall solo a rotte sensibili
        $sensitivePaths = [
            'login',
            'admin/login',
            'owners',
            'properties',
            'reservations',
            'payments'
        ];

        foreach ($sensitivePaths as $protected) {
            if (str_starts_with($path, $protected)) {

                // Limite richieste — 20 richieste in 60 secondi
                $key = "fw_rate_{$ip}";
                $attempts = Cache::get($key, 0) + 1;
                Cache::put($key, $attempts, 60);

                if ($attempts > 20) {
                    return response()->json([
                        'blocked' => true,
                        'reason'  => 'Troppi tentativi, rallenta per favore.',
                        'ip'      => $ip,
                        'time'    => $now
                    ], Response::HTTP_TOO_MANY_REQUESTS);
                }

                // Blocchiamo richieste sospette (SQL injection, XSS)
                $patterns = [
                    '/select.+from/i',
                    '/union.+select/i',
                    '/<script>/i',
                    '/drop\s+table/i',
                    '/insert\s+into/i'
                ];

                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $request->fullUrl())) {
                        return response()->json([
                            'blocked' => true,
                            'reason'  => 'Richiesta malformata / sospetta',
                            'ip'      => $ip,
                            'pattern' => $pattern
                        ], Response::HTTP_FORBIDDEN);
                    }
                }
            }
        }

        return $next($request);
    }
}
