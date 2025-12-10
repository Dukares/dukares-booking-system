<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Reservation;

class ICSImportService
{
    /**
     * Importa un file ICS per una struttura
     *
     * @param  int    $propertyId
     * @param  string $icsContent
     * @return array  [
     *     'imported'   => X,
     *     'duplicates' => Y,
     *     'total'      => Z,
     * ]
     */
    public function importICS($propertyId, $icsContent)
    {
        $events = $this->parseICS($icsContent);

        $imported   = 0;
        $duplicates = 0;
        $total      = 0;

        foreach ($events as $e) {

            if (!isset($e['DTSTART']) || !isset($e['DTEND'])) {
                continue;
            }

            // Normalizziamo le date (solo giorno)
            $checkin  = Carbon::parse($e['DTSTART'])->toDateString();
            $checkout = Carbon::parse($e['DTEND'])->toDateString();

            $nomeOspite = $e['SUMMARY'] ?? 'Import ICS';
            $note       = $e['DESCRIPTION'] ?? null;

            // Chiave univoca per evitare duplicati
            $hashSource = [
                'property_id' => $propertyId,
                'checkin'     => $checkin,
                'checkout'    => $checkout,
                'nome_ospite' => $nomeOspite,
                'note'        => $note,
            ];

            $uniqueHash = md5(json_encode($hashSource));

            // Se esiste già una prenotazione con lo stesso hash → duplicato
            $exists = Reservation::where('unique_hash', $uniqueHash)->exists();

            if ($exists) {
                $duplicates++;
                $total++;
                continue;
            }

            // Crea la prenotazione
            Reservation::create([
                'property_id'  => $propertyId,
                'checkin'      => $checkin,
                'checkout'     => $checkout,
                'nome_ospite'  => $nomeOspite,
                'note'         => $note,
                'channel'      => 'ICS',
                'unique_hash'  => $uniqueHash,
            ]);

            $imported++;
            $total++;
        }

        return [
            'imported'   => $imported,
            'duplicates' => $duplicates,
            'total'      => $total,
        ];
    }

    /**
     * Parser molto semplice del file ICS
     *
     * @param  string $ics
     * @return array
     */
    private function parseICS($ics)
    {
        $lines   = preg_split("/\r\n|\n|\r/", $ics);
        $events  = [];
        $current = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === 'BEGIN:VEVENT') {
                $current = [];
            }
            elseif ($line === 'END:VEVENT') {
                $events[] = $current;
            }
            elseif (strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $current[$key] = $value;
            }
        }

        return $events;
    }
}
