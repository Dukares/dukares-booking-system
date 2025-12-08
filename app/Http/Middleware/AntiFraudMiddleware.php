<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\FraudService;

class AntiFraudMiddleware
{
    public function handle($request, Closure $next, $action)
    {
        $fraud = new FraudService();
        $score = $fraud->calculateScore($action, auth()->id());

        // Se rischio >= 60 blocchiamo
        if ($score >= 60) {
            return response()->view('security.flagged', [
                'score' => $score
            ]);
        }

        return $next($request);
    }
}
