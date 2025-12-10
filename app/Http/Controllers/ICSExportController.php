<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Http\Response;

class ICSExportController extends Controller
{
    public function export($propertyId)
    {
        $property = Property::findOrFail($propertyId);

        // Prendiamo prenotazioni e blocchi calendario
        $events = $property->calendarDays()
            ->whereNotNull('is_blocked')   // giorni bloccati
            ->orWhereNotNull('reservation_id') // prenotazioni
            ->get();

        // Header ICS
        $ics  = "BEGIN:VCALENDAR\r\n";
        $ics .= "VERSION:2.0\r\n";
        $ics .= "PRODID:-//DukaRes//ICS Export//EN\r\n";

        foreach ($events as $ev) {

            $start = Carbon::parse($ev->date)->format('Ymd');
            $end   = Carbon::parse($ev->date)->addDay()->format('Ymd');

            $uid = md5($property->id . $ev->date);

            $ics .= "BEGIN:VEVENT\r\n";
            $ics .= "UID:$uid\r\n";
            $ics .= "DTSTAMP:" . now()->format('Ymd\THis') . "\r\n";
            $ics .= "DTSTART;VALUE=DATE:$start\r\n";
            $ics .= "DTEND;VALUE=DATE:$end\r\n";
            $ics .= "SUMMARY:Occupato\r\n";
            $ics .= "END:VEVENT\r\n";
        }

        $ics .= "END:VCALENDAR\r\n";

        return new Response($ics, 200, [
            'Content-Type' => 'text/calendar',
            'Content-Disposition' => 'inline; filename="property_'.$propertyId.'.ics"',
        ]);
    }
}
