<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Room;
use App\Models\RoomPricing;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * MOSTRA IL CALENDARIO STILE BOOKING
     */
    public function index(Property $property, Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        $carbonMonth = Carbon::parse($month);

        $daysInMonth = $carbonMonth->daysInMonth;

        // Carica tutte le camere della struttura
        $rooms = $property->rooms()->get();

        // Carica prezzi per tutto il mese
        $pricing = RoomPricing::whereIn('room_id', $rooms->pluck('id'))
            ->whereBetween('data', [
                $carbonMonth->startOfMonth()->format('Y-m-d'),
                $carbonMonth->endOfMonth()->format('Y-m-d')
            ])
            ->get()
            ->groupBy('room_id');

        return view('calendar.index', [
            'property'     => $property,
            'rooms'        => $rooms,
            'pricing'      => $pricing,
            'month'        => $month,
            'daysInMonth'  => $daysInMonth
        ]);
    }



    /**
     * SALVA LE MODIFICHE DI UN GIORNO SINGOLO
     */
    public function updateSingle(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'date'    => 'required|date',
        ]);

        RoomPricing::updateOrCreate(
            [
                'room_id' => $request->room_id,
                'data'    => $request->date
            ],
            [
                'prezzo'           => $request->prezzo,
                'prezzo_adulto'    => $request->prezzo_adulto,
                'prezzo_bambino'   => $request->prezzo_bambino,
                'minimo_soggiorno' => $request->minimo_soggiorno,
                'disponibilita'    => $request->disponibilita,
                'extra_ospite'     => $request->extra_ospite,
                'max_ospiti'       => $request->max_ospiti,
            ]
        );

        return back()->with('success', 'Giorno aggiornato correttamente!');
    }



    /**
     * SALVA RANGE DI DATE (MODIFICHE MASSIVE)
     */
    public function updateRange(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'from'    => 'required|date',
            'to'      => 'required|date',
        ]);

        $period = Carbon::parse($request->from)
                    ->daysUntil(Carbon::parse($request->to)->addDay());

        foreach ($period as $date) {
            RoomPricing::updateOrCreate(
                [
                    'room_id' => $request->room_id,
                    'data'    => $date->format('Y-m-d')
                ],
                [
                    'prezzo'           => $request->prezzo,
                    'prezzo_adulto'    => $request->prezzo_adulto,
                    'prezzo_bambino'   => $request->prezzo_bambino,
                    'minimo_soggiorno' => $request->minimo_soggiorno,
                    'disponibilita'    => $request->disponibilita,
                    'extra_ospite'     => $request->extra_ospite,
                    'max_ospiti'       => $request->max_ospiti,
                ]
            );
        }

        return back()->with('success', 'Modifiche applicate a tutte le date selezionate!');
    }
}
