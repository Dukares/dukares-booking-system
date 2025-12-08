<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePhoneIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user->phone || !$user->phone_verified_at) {
            return redirect()->route('security.phone.show')
                ->with('status', 'Per continuare devi verificare il tuo numero di telefono.');
        }

        return $next($request);
    }
}
