<?php

namespace App\Services;

use Detection\MobileDetect;

class AgentService
{
    protected $detect;

    public function __construct()
    {
        $this->detect = new MobileDetect;
    }

    public function browser()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    }

    public function os()
    {
        if ($this->detect->isAndroidOS()) return 'Android';
        if ($this->detect->isIOS()) return 'iOS';
        if ($this->detect->isWindowsPhoneOS()) return 'Windows Phone';

        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

        if (stripos($ua, 'Windows') !== false) return 'Windows';
        if (stripos($ua, 'Mac') !== false) return 'MacOS';
        if (stripos($ua, 'Linux') !== false) return 'Linux';

        return 'Unknown OS';
    }

    public function deviceType()
    {
        if ($this->detect->isTablet()) return 'Tablet';
        if ($this->detect->isMobile()) return 'Mobile';
        return 'Desktop';
    }
}
