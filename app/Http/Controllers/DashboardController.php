<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Owner;
use App\Models\Reservation;
use App\Models\ReservationPayment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /* ----------------------------------------------
         * 1) STATISTICHE IN ALTO
         * ---------------------------------------------- */
        $totProperties = Property::count();
        $totOwners = Owner::count();

        $totBookingsMonth = Reservation::whereMonth('checkin', now()->month)
            ->whereYear('checkin', now()->year)
            ->count();

        $totCommissioni = ReservationPayment::sum('commissione_dukares');

        /* ----------------------------------------------
         * 2) ULTIME 8 PRENOTAZIONI
         * ---------------------------------------------- */
        $recentBookings = Reservation::with('property')
            ->orderBy('checkin', 'desc')
            ->limit(8)
            ->get();

        /* ----------------------------------------------
         * 3) PRENOTAZIONI PER MESE (GRAFICO)
         * ---------------------------------------------- */
        $bookingsByMonth = Reservation::selectRaw('MONTH(checkin) as mese, COUNT(*) as tot')
            ->groupBy('mese')
            ->orderBy('mese')
            ->get();

        /* ----------------------------------------------
         * 4) PASSO I DATI ALLA VIEW
         * ---------------------------------------------- */
        return view('dashboard.index', compact(
            'totProperties',
            'totOwners',
            'totBookingsMonth',
            'totCommissioni',
            'recentBookings',
            'bookingsByMonth'
        ));
    }
}
