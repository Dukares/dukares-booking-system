<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyCalendar;
use App\Models\Owner;
use App\Services\CalendarService;
use Carbon\Carbon;

class PropertyController extends Controller
{
    /**
     * LISTA STRUTTURE
     */
    public function index()
    {
        $properties = Property::with('owner')->get();
        return view('properties.index', compact('properties'));
    }

    /**
     * FORM NUOVA STRUTTURA
     */
    public function create()
    {
        $owners = Owner::all();
        return view('properties.create', compact('owners'));
    }

    /**
     * SALVATAGGIO NUOVA STRUTTURA + PMS CALENDAR
     */
    public function store(Request $request)
    {
        $request->validate([
            'owner_id'   => 'required|exists:owners,id',
            'name'       => 'required|string|max:255',
            'address'    => 'required|string|max:255',
            'description'=> 'nullable|string'
        ]);

        $property = Property::create([
            'owner_id'   => $request->owner_id,
            'name'       => $request->name,
            'address'    => $request->address,
            'description'=> $request->description,
        ]);

        // Genera 365 giorni del calendario PMS
        $calendar = new CalendarService();
        $calendar->generateCalendarForProperty($property->id);

        return redirect()
            ->route('properties.index')
            ->with('success', 'Struttura creata con successo.');
    }

    /**
     * DETTAGLI STRUTTURA
     */
    public function show($id)
    {
        $property = Property::findOrFail($id);
        return view('properties.show', compact('property'));
    }

    /**
     * FORM MODIFICA
     */
    public function edit($id)
    {
        $property = Property::findOrFail($id);
        $owners   = Owner::all();
        return view('properties.edit', compact('property', 'owners'));
    }

    /**
     * SALVA MODIFICA
     */
    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        $request->validate([
            'owner_id'   => 'required|exists:owners,id',
            'name'       => 'required|string|max:255',
            'address'    => 'required|string|max:255',
            'description'=> 'nullable|string'
        ]);

        $property->update([
            'owner_id'   => $request->owner_id,
            'name'       => $request->name,
            'address'    => $request->address,
            'description'=> $request->description,
        ]);

        return redirect()
            ->route('properties.index')
            ->with('success', 'Struttura aggiornata con successo.');
    }

    /**
     * ELIMINA STRUTTURA
     */
    public function destroy($id)
    {
        Property::destroy($id);

        return redirect()
            ->route('properties.index')
            ->with('success', 'Struttura eliminata.');
    }

    /**
     * ============================================================
     *  CALENDARIO PMS — VISUALIZZAZIONE MENSILE
     * ============================================================
     */
    public function calendar(Request $request, $propertyId)
    {
        $property = Property::findOrFail($propertyId);

        // Mese via ?month=2025-12 oppure mese corrente
        $monthParam = $request->query('month');

        $currentMonth = $monthParam
            ? Carbon::createFromFormat('Y-m', $monthParam)->startOfMonth()
            : Carbon::now()->startOfMonth();

        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth   = $currentMonth->copy()->endOfMonth();

        // Mostrare calendario completo (Lunedì → Domenica)
        $startGrid = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endGrid   = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        // Preleva giorni PMS
        $days = PropertyCalendar::where('property_id', $property->id)
            ->whereBetween('date', [
                $startGrid->format('Y-m-d'),
                $endGrid->format('Y-m-d')
            ])
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Matrice SETTIMANE → GIORNI
        $calendar = [];
        $day = $startGrid->copy();

        while ($day->lte($endGrid)) {
            $week = [];

            for ($i = 0; $i < 7; $i++) {
                $dateString = $day->format('Y-m-d');

                $week[] = [
                    'date'       => $day->copy(),
                    'in_current' => $day->month == $currentMonth->month,
                    'entry'      => $days->get($dateString)
                ];

                $day->addDay();
            }

            $calendar[] = $week;
        }

        return view('properties.calendar', [
            'property'     => $property,
            'calendar'     => $calendar,
            'currentMonth' => $currentMonth,
            'prevMonth'    => $currentMonth->copy()->subMonth()->format('Y-m'),
            'nextMonth'    => $currentMonth->copy()->addMonth()->format('Y-m'),
        ]);
    }

    /**
     * ============================================================
     *  AJAX — AGGIORNA UN GIORNO DEL CALENDARIO
     * ============================================================
     */
    public function updateDay(Request $request)
    {
        $request->validate([
            'property_id' => 'required|integer',
            'date'        => 'required|date',
            'price'       => 'nullable|numeric',
            'min_stay'    => 'nullable|integer|min:1',
            'status'      => 'required|string|in:available,booked,closed',
        ]);

        $entry = PropertyCalendar::where('property_id', $request->property_id)
            ->where('date', $request->date)
            ->first();

        if (!$entry) {
            return response()->json(['error' => 'Giorno non trovato'], 404);
        }

        $entry->price    = $request->price;
        $entry->min_stay = $request->min_stay;
        $entry->status   = $request->status;
        $entry->source   = "manual";
        $entry->save();

        return response()->json([
            'success' => true,
            'message' => 'Aggiornato con successo',
            'status'  => $entry->status,
            'price'   => $entry->price,
        ]);
    }

    /**
     * ============================================================
     *  STEP 4 — RANGE MULTIPLO (seleziona più giorni)
     * ============================================================
     */
    public function updateRange(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date',
            'status'      => 'required|string|in:available,booked,closed',
            'price'       => 'nullable|numeric',
            'min_stay'    => 'nullable|integer|min:1'
        ]);

        $dates = \Carbon\CarbonPeriod::create($request->start_date, $request->end_date);

        foreach ($dates as $date) {
            PropertyCalendar::updateOrCreate(
                [
                    'property_id' => $request->property_id,
                    'date'        => $date->format('Y-m-d')
                ],
                [
                    'status'   => $request->status,
                    'price'    => $request->price,
                    'min_stay' => $request->min_stay,
                    'source'   => 'manual'
                ]
            );
        }

        return [
            'success' => true,
            'message' => 'Intervallo aggiornato correttamente.'
        ];
    }
}
