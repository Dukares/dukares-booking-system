<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Reservation;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Monthly calendar view
     */
    public function index(Request $request)
    {
        $properties = Property::orderBy('title')->get();

        if ($properties->isEmpty()) {
            return view('calendar.empty');
        }

        $propertyId = $request->get('property_id') ?? $properties->first()->id;
        $property   = Property::findOrFail($propertyId);

        $month = $request->get('month')
            ? Carbon::parse($request->get('month'))->startOfMonth()
            : now()->startOfMonth();

        $start = $month->copy()->startOfMonth();
        $end   = $month->copy()->endOfMonth();

        $reservations = Reservation::where('property_id', $propertyId)
            ->whereDate('checkin', '<=', $end)
            ->whereDate('checkout', '>=', $start)
            ->orderBy('checkin')
            ->get();

        return view('calendar.index', compact(
            'property',
            'properties',
            'month',
            'reservations'
        ));
    }

    /**
     * Single day view (click on a calendar day)
     */
    public function day(Request $request)
    {
        $properties = Property::orderBy('title')->get();

        if ($properties->isEmpty()) {
            return view('calendar.empty');
        }

        $propertyId = $request->get('property_id') ?? $properties->first()->id;
        $property   = Property::findOrFail($propertyId);

        $date = $request->get('date')
            ? Carbon::parse($request->get('date'))->startOfDay()
            : now()->startOfDay();

        // Reservations covering this day (checkin <= date < checkout)
        $dayBookings = Reservation::where('property_id', $propertyId)
            ->whereDate('checkin', '<=', $date)
            ->whereDate('checkout', '>', $date)
            ->orderBy('checkin')
            ->get();

        $month = $request->get('month')
            ? Carbon::parse($request->get('month'))->startOfMonth()
            : $date->copy()->startOfMonth();

        return view('calendar.day', compact(
            'property',
            'properties',
            'propertyId',
            'date',
            'dayBookings',
            'month'
        ));
    }

    /**
     * Create reservation from calendar
     * STEP 5 – Availability Engine (NO OVERBOOKING)
     */
    public function store(Request $request, AvailabilityService $availability)
    {
        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'guest_name'  => 'required|string|max:255',
            'checkin'     => 'required|date',
            'checkout'    => 'required|date|after:checkin',
            'total_price' => 'nullable|numeric|min:0',
            'status'      => 'required|string|max:50',
            'notes'       => 'nullable|string',
        ]);

        $checkin  = Carbon::parse($data['checkin'])->startOfDay();
        $checkout = Carbon::parse($data['checkout'])->startOfDay();

        // 🔒 CENTRAL AVAILABILITY CHECK (CORE ENGINE)
        if (!$availability->isAvailable(
            $data['property_id'],
            $checkin,
            $checkout
        )) {
            return back()
                ->withErrors([
                    'checkin' => 'Selected dates are not available for this property.',
                ])
                ->withInput();
        }

        Reservation::create([
            'property_id' => $data['property_id'],
            'guest_name'  => $data['guest_name'],
            'checkin'     => $checkin,
            'checkout'    => $checkout,
            'total_price' => $data['total_price'] ?? 0,
            'status'      => $data['status'],
            'notes'       => $data['notes'] ?? null,
        ]);

        // Always redirect back to monthly calendar
        return redirect()
            ->route('calendar.index', [
                'property_id' => $data['property_id'],
                'month'       => $checkin->format('Y-m'),
            ])
            ->with('success', 'Reservation created successfully.');
    }
}
