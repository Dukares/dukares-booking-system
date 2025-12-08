<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Property;

class ReservationController extends Controller
{
    /**
     * Lista prenotazioni
     */
    public function index()
    {
        $reservations = Reservation::with('property')->get();
        return view('reservations.index', compact('reservations'));
    }

    /**
     * Form nuova prenotazione
     */
    public function create()
    {
        // FIX: mancava questa variabile
        $properties = Property::all();

        return view('reservations.create', compact('properties'));
    }

    /**
     * Salvataggio prenotazione
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'property_id' => 'required|exists:properties,id',
            'checkin' => 'required|date',
            'checkout' => 'required|date|after:checkin',
            'price' => 'required|numeric',
            'status' => 'required|string'
        ]);

        Reservation::create($validated);

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Prenotazione creata con successo.');
    }

    /**
     * Modifica prenotazione
     */
    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);

        // FIX: mancava anche qui
        $properties = Property::all();

        return view('reservations.edit', compact('reservation', 'properties'));
    }

    /**
     * Aggiorna prenotazione
     */
    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'property_id' => 'required|exists:properties,id',
            'checkin' => 'required|date',
            'checkout' => 'required|date|after:checkin',
            'price' => 'required|numeric',
            'status' => 'required|string'
        ]);

        $reservation->update($validated);

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Prenotazione aggiornata con successo.');
    }

    /**
     * Cancella prenotazione
     */
    public function destroy($id)
    {
        Reservation::destroy($id);

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Prenotazione eliminata.');
    }
}
