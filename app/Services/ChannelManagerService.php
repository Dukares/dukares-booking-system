<?php

namespace App\Services;

use App\Services\Channels\AirbnbChannel;
use App\Services\Channels\GoogleICSChannel;

class ChannelManagerService
{
    public static function getChannel($property, $channelName)
    {
        return match ($channelName) {
            'google_ics' => new GoogleICSChannel($property),
            'airbnb_ics' => new AirbnbChannel($property),
            default => null,
        };
    }

    // Import da tutti i canali
    public static function importAll($property)
    {
        $results = [];

        $channels = [
            'google_ics',
            'airbnb_ics'
        ];

        foreach ($channels as $ch) {
            $provider = self::getChannel($property, $ch);

            if ($provider) {
                $results[$ch] = $provider->import();
            }
        }

        return $results;
    }
}
