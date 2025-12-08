<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function show()
    {
        // Se non c'è OTP in sessione, torna alla dashboard o al login
        if (!session()->has('otp_code') || !session()->has('otp_for_user_id')) {
            return redirect('/dashboard');
        }

        return view('auth.otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $sessionCode   = session('otp_code');
        $expiresAt     = session('otp_expires_at');
        $otpUserId     = session('otp_for_user_id');

        if (!$sessionCode || !$otpUserId) {
            return redirect('/login')->withErrors(['error' => 'Sessione OTP scaduta o non valida.']);
        }

        if (now()->greaterThan($expiresAt)) {
            session()->forget(['otp_code', 'otp_expires_at', 'otp_for_user_id']);
            return redirect('/login')->withErrors(['error' => 'Codice OTP scaduto. Effettua di nuovo il login.']);
        }

        if ($request->code !== $sessionCode) {
            return back()->withErrors(['error' => 'Codice OTP non valido.']);
        }

        // OTP corretta → pulizia sessione OTP
        session()->forget(['otp_code', 'otp_expires_at', 'otp_for_user_id']);

        return redirect('/dashboard')->with('success', 'Verifica OTP completata con successo.');
    }
}

