<?php

namespace App\Services;

use App\Models\PropertyCalendar;
use Carbon\Carbon;

class CalendarService
{
    /**
     * Genera 365 giorni di calendario per una proprietà appena creata.
     */
    public function generateCalendarForProperty($propertyId)
    {
        $today = Carbon::today();

        for ($i = 0; $i < 365; $i++) {
            $date = $today->copy()->addDays($i)->format('Y-m-d');

            PropertyCalendar::firstOrCreate(
                [
                    'property_id' => $propertyId,
                    'date' => $date
                ],
                [
                    'status' => 'available',
                    'price'  => null,
                    'source' => 'dukare'
                ]
            );
        }
    }

    /**
     * Blocca un intervallo di date (prenotazione / import iCal)
     */
    public function blockDates($propertyId, $startDate, $endDate, $source = 'manual')
    {
        $start = Carbon::parse($startDate);
        $end   = Carbon::parse($endDate);

        while ($start->lte($end)) {
            PropertyCalendar::updateOrCreate(
                [
                    'property_id' => $propertyId,
                    'date' => $start->format('Y-m-d')
                ],
                [
                    'status' => 'booked',
                    'source' => $source
                ]
            );

            $start->addDay();
        }
    }

    /**
     * Verifica se un intervallo è disponibile (anti-overbooking)
     */
    public function isAvailable($propertyId, $startDate, $endDate)
    {
        return !PropertyCalendar::where('property_id', $propertyId)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 'booked')
            ->exists();
    }
}
