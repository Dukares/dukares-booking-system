<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request as HttpRequest;

use App\Services\AgentService;
use App\Services\IpRiskService;
use App\Services\BotDefenseService;

use App\Models\DeviceLog;
use App\Mail\NewDeviceAlertMail;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validazione input base
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = $request->email;

        // ---------------------------------------------------------
        // 1️⃣ ANTIBOT: ANALISI PRIMA DEL LOGIN
        // ---------------------------------------------------------
        $bot = new BotDefenseService();
        $botResult = $bot->analyze($email);

        // Se rischio altissimo → blocco immediato
        if ($botResult['action'] === 'block') {
            $bot->logFailed($email);
            return back()->withErrors([
                'error' => "Accesso bloccato per attività sospetta (BotDefense)."
            ]);
        }

        // Tentativo login
        if (!Auth::attempt($request->only('email', 'password'))) {

            // Registra tentativo fallito
            $bot->logFailed($email);

            // Se rischio medio → richiede OTP (per sicurezza)
            if ($botResult['action'] === 'otp') {
                return back()->withErrors([
                    'error' => "Per sicurezza devi effettuare una verifica aggiuntiva (OTP)."
                ]);
            }

            return back()->withErrors(['error' => 'Credenziali non valide.']);
        }

        // LOGIN riuscito
        $user = Auth::user();

        // ---------------------------------------------------------
        // 2️⃣ RILEVAZIONE DISPOSITIVO
        // ---------------------------------------------------------
        $agent = new AgentService();

        $ip = HttpRequest::ip();
        $browser = $agent->browser();
        $os = $agent->os();
        $deviceType = $agent->deviceType();

        $fingerprint = sha1($ip . $browser . $os . $deviceType);

        $device = DeviceLog::where('user_id', $user->id)
            ->where('device_fingerprint', $fingerprint)
            ->first();

        // ---------------------------------------------------------
        // 3️⃣ IP RISK ENGINE
        // ---------------------------------------------------------
        $riskEngine = new IpRiskService();
        $riskData = $riskEngine->analyze($ip);

        $ipRisk = $riskData['risk'];


        // ---------------------------------------------------------
        // 4️⃣ RISCHIO ALTISSIMO → BLOCCO LOGIN
        // ---------------------------------------------------------
        if ($ipRisk > 85) {
            Auth::logout();
            return back()->withErrors([
                'error' => "Accesso bloccato per sicurezza. Rischio rete troppo elevato (>$ipRisk%)"
            ]);
        }


        // ---------------------------------------------------------
        // 5️⃣ RISCHIO ALTO (70–85) → PASSKEY OBBLIGATORIA
        // ---------------------------------------------------------
        if ($ipRisk >= 70 && $ipRisk <= 85) {

            if ($user->webauthnCredentials()->count() == 0) {
                Auth::logout();
                return back()->withErrors([
                    'error' => "Accesso ad alto rischio: devi registrare una Passkey per continuare."
                ]);
            }

            session(['risk_high_user' => $user->id]);

            return redirect()->route('passkey.challenge');
        }


        // ---------------------------------------------------------
        // 6️⃣ RISCHIO MEDIO (50–70) → OTP OBBLIGATORIA
        // ---------------------------------------------------------
        if ($ipRisk >= 50 && $ipRisk < 70) {

            $code = random_int(100000, 999999);

            session([
                'otp_code'        => $code,
                'otp_expires_at'  => now()->addMinutes(5),
                'otp_for_user_id' => $user->id
            ]);

            Mail::raw("Il tuo codice OTP DukaRes: {$code}", function ($m) use ($user) {
                $m->to($user->email)->subject('Codice OTP di Sicurezza');
            });

            return redirect()->route('otp.show');
        }


        // ---------------------------------------------------------
        // 7️⃣ DISPOSITIVO NUOVO → CREA + EMAIL + FLAG
        // ---------------------------------------------------------
        if (!$device) {

            DeviceLog::create([
                'user_id' => $user->id,
                'ip' => $ip,
                'browser' => $browser,
                'os' => $os,
                'device_fingerprint' => $fingerprint,
                'logged_in_at' => now(),
                'last_used_at' => now(),
                'risk_level' => max($ipRisk, 60),
                'is_suspicious' => true,
                'is_trusted' => false
            ]);

            Mail::to($user->email)->send(
                new NewDeviceAlertMail($ip, $browser, $os, $deviceType)
            );

            session()->flash('security_alert', [
                'ip' => $ip,
                'browser' => $browser,
                'os' => $os,
                'deviceType' => $deviceType
            ]);
        }


        // ---------------------------------------------------------
        // 8️⃣ DISPOSITIVO ESISTENTE → AGGIORNA
        // ---------------------------------------------------------
        else {

            $finalRisk = $ipRisk;

            if ($device->ip !== $ip) {
                $finalRisk = max($finalRisk, 75);
            }

            $device->update([
                'ip' => $ip,
                'last_used_at' => now(),
                'risk_level' => $finalRisk,
                'is_suspicious' => $finalRisk >= 50
            ]);
        }

        // ---------------------------------------------------------
        // 9️⃣ ACCESSO CONSENTITO
        // ---------------------------------------------------------
        return redirect('/dashboard');
    }
}

