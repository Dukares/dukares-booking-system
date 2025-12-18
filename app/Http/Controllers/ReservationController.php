<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Property;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * List reservations
     */
    public function index()
    {
        $reservations = Reservation::with('property')
            ->orderBy('checkin', 'desc')
            ->get();

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show create reservation form
     */
    public function create()
    {
        $properties = Property::orderBy('title')->get();

        return view('reservations.create', compact('properties'));
    }

    /**
     * Store reservation (USER-FRIENDLY DATES)
     */
    public function store(Request $request)
    {
        // 1️⃣ VALIDATION (select day/month/year)
        $request->validate([
            'property_id'     => 'required|exists:properties,id',
            'guest_name'      => 'required|string|max:255',

            'checkin_day'     => 'required|integer|min:1|max:31',
            'checkin_month'   => 'required|integer|min:1|max:12',
            'checkin_year'    => 'required|integer|min:2000',

            'checkout_day'    => 'required|integer|min:1|max:31',
            'checkout_month'  => 'required|integer|min:1|max:12',
            'checkout_year'   => 'required|integer|min:2000',

            'status'          => 'required|string|max:50',
            'notes'           => 'nullable|string',
        ]);

        // 2️⃣ BUILD DATES (YYYY-MM-DD)
        $checkin = sprintf(
            '%04d-%02d-%02d',
            $request->checkin_year,
            $request->checkin_month,
            $request->checkin_day
        );

        $checkout = sprintf(
            '%04d-%02d-%02d',
            $request->checkout_year,
            $request->checkout_month,
            $request->checkout_day
        );

        // 3️⃣ BASIC DATE LOGIC CHECK
        if ($checkout <= $checkin) {
            return back()
                ->withErrors(['checkout' => 'Check-out must be after check-in'])
                ->withInput();
        }

        // 4️⃣ SAVE RESERVATION
        Reservation::create([
            'property_id' => $request->property_id,
            'guest_name'  => $request->guest_name,
            'checkin'     => $checkin,
            'checkout'    => $checkout,
            'status'      => $request->status,
            'notes'       => $request->notes,
        ]);

        // 5️⃣ REDIRECT
        return redirect()
            ->route('reservations.index')
            ->with('success', 'Reservation created successfully.');
    }

    /**
     * Edit reservation (placeholder)
     */
    public function edit(Reservation $reservation)
    {
        return view('reservations.edit', compact('reservation'));
    }
}
