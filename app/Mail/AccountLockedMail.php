<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountLockedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ip;
    public $ua;
    public $reasons;
    public $unlockUrl;

    public function __construct($ip, $ua, $reasons)
    {
        $this->ip = $ip;
        $this->ua = $ua;
        $this->reasons = $reasons;

        // Generiamo token di sblocco unico
        $token = sha1($ip . $ua . time());

        // URL per sbloccare
        $this->unlockUrl = url('/unlock-account?token=' . $token);

        // salviamo il token in sessione (ma dopo faremo il DB)
        session(['unlock_token' => $token]);
    }

    public function build()
    {
        return $this
            ->subject('⚠️ Il tuo account DukaRes è stato bloccato per sicurezza')
            ->markdown('emails.security.account_locked');
    }
}

