<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PropertyCalendar;

class PropertyCalendarController extends Controller
{
    /**
     * Aggiorna un singolo giorno del calendario (AJAX)
     */
    public function updateDay(Request $request)
    {
        $validated = $request->validate([
            'id'     => 'required|exists:property_calendar,id',
            'status' => 'required|in:available,booked,closed',
            'price'  => 'nullable|numeric',
            'source' => 'nullable|string'
        ]);

        $entry = PropertyCalendar::findOrFail($validated['id']);
        $entry->status = $validated['status'];
        $entry->price = $validated['price'];
        $entry->source = $validated['source'];
        $entry->save();

        return response()->json([
            'success' => true,
            'message' => 'Giorno aggiornato correttamente',
            'entry'   => $entry
        ]);
    }
}
