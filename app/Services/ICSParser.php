<?php

namespace App\Services;

class ICSParser
{
    /**
     * Legge un file ICS e restituisce un array di eventi
     */
    public function parse(string $icsContent): array
    {
        $lines = explode("\n", $icsContent);

        $events = [];
        $currentEvent = null;

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === 'BEGIN:VEVENT') {
                $currentEvent = [];
            } elseif ($line === 'END:VEVENT') {
                if ($currentEvent) {
                    $events[] = $currentEvent;
                }
                $currentEvent = null;
            } elseif ($currentEvent !== null) {
                [$key, $value] = array_pad(explode(":", $line, 2), 2, null);
                $currentEvent[$key] = $value;
            }
        }

        return $events;
    }
}
