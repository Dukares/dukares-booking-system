<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewDeviceAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ip;
    public $browser;
    public $os;
    public $deviceType;

    public function __construct($ip, $browser, $os, $deviceType)
    {
        $this->ip = $ip;
        $this->browser = $browser;
        $this->os = $os;
        $this->deviceType = $deviceType;
    }

    public function build()
    {
        return $this
            ->subject('⚠️ Nuovo accesso al tuo account DukaRes')
            ->view('emails.new-device-alert');
    }
}
