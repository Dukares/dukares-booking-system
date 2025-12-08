<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laragear\WebAuthn\Facades\WebAuthn;

class PasskeyController extends Controller
{
    /**
     * Avvia la registrazione della passkey (creazione credenziale)
     */
    public function registerBegin(Request $request)
    {
        // Genera la challenge iniziale
        $options = WebAuthn::prepareAttestation($request->user());

        return response()->json($options);
    }

    /**
     * Completa la registrazione della passkey
     */
    public function registerFinish(Request $request)
    {
        // Verifica la challenge e salva la passkey
        WebAuthn::verifyAttestation($request->user(), $request);

        return response()->json(['success' => true, 'message' => 'Passkey registrata con successo!']);
    }

    /**
     * Inizia la fase di login con passkey (autenticazione)
     */
    public function loginBegin(Request $request)
    {
        $email = $request->input('email');

        // Prepara la challenge per il login biometrico
        $options = WebAuthn::prepareAssertion($email);

        return response()->json($options);
    }

    /**
     * Completa il login tramite passkey
     */
    public function loginFinish(Request $request)
    {
        // Verifica la risposta WebAuthn e restituisce l’utente
        $user = WebAuthn::verifyAssertion($request);

        auth()->login($user);

        return response()->json(['success' => true, 'redirect' => '/dashboard']);
    }
}
