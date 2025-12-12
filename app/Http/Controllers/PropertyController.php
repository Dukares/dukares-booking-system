<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Owner;
use Illuminate\Http\Request;
use App\Services\ICSImportService;

class PropertyController extends Controller
{
    /**
     * Lista strutture
     */
    public function index()
    {
        $properties = Property::with('owner')->get();
        return view('properties.index', compact('properties'));
    }

    /**
     * Form creazione struttura
     */
    public function create()
    {
        $owners = Owner::all();
        return view('properties.create', compact('owners'));
    }

    /**
     * Salvataggio nuova struttura
     */
    public function store(Request $request)
    {
        $request->validate([
            'owner_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'stars' => 'nullable|integer',
            'property_type' => 'nullable|string|max:255',
            'ics_url' => 'nullable|string|max:500',
        ]);

        Property::create([
            'owner_id' => $request->owner_id,
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'stars' => $request->stars,
            'property_type' => $request->property_type,
            'ics_url' => $request->ics_url,
        ]);

        return redirect()->route('properties.index')
                         ->with('success', 'Struttura creata con successo.');
    }

    /**
     * Form modifica struttura
     */
    public function edit(Property $property)
    {
        $owners = Owner::all();
        return view('properties.edit', compact('property', 'owners'));
    }

    /**
     * Aggiornamento struttura
     */
    public function update(Request $request, Property $property)
    {
        $request->validate([
            'owner_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'stars' => 'nullable|integer',
            'property_type' => 'nullable|string|max:255',
            'ics_url' => 'nullable|string|max:500',
        ]);

        $property->update([
            'owner_id' => $request->owner_id,
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'stars' => $request->stars,
            'property_type' => $request->property_type,
            'ics_url' => $request->ics_url,
        ]);

        return redirect()->route('properties.index')
                         ->with('success', 'Struttura aggiornata con successo.');
    }

    /**
     * Eliminazione struttura
     */
    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()->route('properties.index')
                         ->with('success', 'Struttura eliminata con successo.');
    }

    /**
     * CALENDARIO STRUTTURA
     */
    public function calendar(Property $property)
    {
        return view('properties.calendar', compact('property'));
    }

    /**
     * Aggiornamento singolo giorno (PMS)
     */
    public function updateDay(Request $request)
    {
        // TODO: implementazione se necessaria
        return response()->json(['status' => 'ok']);
    }

    /**
     * Aggiornamento intervallo giorni (PMS)
     */
    public function updateRange(Request $request)
    {
        // TODO: implementazione se necessaria
        return response()->json(['status' => 'ok']);
    }

    /**
     * ⭐ PAGINA CHANNEL MANAGER
     */
    public function channelManager(Property $property)
    {
        $icsExportUrl = url("/ics/property/{$property->id}.ics");

        // Log ICS
        $logPath = storage_path("logs/ics_import.log");
        $logs = file_exists($logPath) ? array_slice(array_reverse(file($logPath)), 0, 10) : [];

        return view('properties.channel-manager', compact('property', 'icsExportUrl', 'logs'));
    }

    /**
     * ⭐ SYNC NOW (Import ICS live da browser)
     */
    public function syncNow(Property $property)
    {
        if (!$property->ics_url) {
            return back()->with('error', 'Questa struttura non ha un ICS URL configurato.');
        }

        try {
            $icsContent = @file_get_contents($property->ics_url);

            if (!$icsContent) {
                return back()->with('error', 'Impossibile scaricare il file ICS.');
            }

            $service = new ICSImportService();
            $result = $service->importICS($property->id, $icsContent);

            return back()->with('success', "Sincronizzazione completata! Importati {$result['imported']} eventi.");

        } catch (\Exception $e) {
            return back()->with('error', 'Errore durante sincronizzazione: '.$e->getMessage());
        }
    }

    /**
     * ⭐ NUOVO — CALENDARIO MENSILE (AJAX)
     */
    public function loadMonth(Request $request, Property $property)
    {
        $month = intval($request->query('month'));
        $year  = intval($request->query('year'));

        // ⭐ Aggiustamento mese fuori range
        if ($month < 1) {
            $month = 12;
            $year--;
        }

        if ($month > 12) {
            $month = 1;
            $year++;
        }

        // ⭐ Recupera tutti i giorni del mese per quella struttura
        $days = $property->calendarDays()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->keyBy('date');

        return view('properties.partials.calendar-month', [
            'property' => $property,
            'month'    => $month,
            'year'     => $year,
            'days'     => $days
        ]);
    }
}
