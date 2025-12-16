<?php

namespace App\Services;

use App\Models\CalendarDay;
use App\Models\Property;
use Carbon\Carbon;

class ICSImportService
{
    /**
     * Importa un file ICS e blocca i giorni nel calendario
     *
     * @return int Numero di giorni importati
     */
    public function import(Property $property, string $icsUrl): int
    {
        $content = @file_get_contents($icsUrl);

        if (! $content) {
            return 0;
        }

        $lines = preg_split("/\r\n|\n|\r/", $content);
        $dates = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if (str_starts_with($line, 'DTSTART')) {
                $dates[] = $this->parseDate($line);
            }

            if (str_starts_with($line, 'DTEND')) {
                // DTEND non include l’ultimo giorno → -1
                $dates[] = $this->parseDate($line)->subDay();
            }
        }

        $imported = 0;

        for ($i = 0; $i < count($dates); $i += 2) {
            if (! isset($dates[$i + 1])) {
                continue;
            }

            $start = $dates[$i];
            $end   = $dates[$i + 1];

            while ($start->lte($end)) {
                CalendarDay::updateOrCreate(
                    [
                        'property_id' => $property->id,
                        'date'        => $start->toDateString(),
                    ],
                    [
                        'status' => 'booked',
                        'source' => 'ics',
                    ]
                );

                $start->addDay();
                $imported++;
            }
        }

        return $imported;
    }

    /**
     * Estrae la data da una riga DTSTART / DTEND
     */
    private function parseDate(string $line): Carbon
    {
        preg_match('/:(\d{8})/', $line, $matches);

        return Carbon::createFromFormat('Ymd', $matches[1]);
    }
}
