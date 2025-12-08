<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\ReservationPayment;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HostDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // TROVA LE PROPRIETÀ DEL PROPRIETARIO
        $properties = Property::where('owner_id', $user->id)->pluck('id');

        $today = Carbon::today();
        $startMonth = $today->copy()->startOfMonth();
        $endMonth   = $today->copy()->endOfMonth();

        // ARRIVI
        $arriviOggi = Reservation::whereIn('property_id', $properties)
            ->whereDate('checkin', $today)
            ->whereNotIn('stato', ['cancellato'])
            ->get();

        // PARTENZE
        $partenzeOggi = Reservation::whereIn('property_id', $properties)
            ->whereDate('checkout', $today)
            ->whereNotIn('stato', ['cancellato'])
            ->get();

        // SOGGIORNI IN CORSO
        $soggiorniInCorso = Reservation::whereIn('property_id', $properties)
            ->whereDate('checkin', '<=', $today)
            ->whereDate('checkout', '>', $today)
            ->whereNotIn('stato', ['cancellato'])
            ->get();

        // KPI
        $prenotazioniMese = Reservation::whereIn('property_id', $properties)
            ->whereBetween('checkin', [$startMonth, $endMonth])
            ->get();

        $pagamentiMese = ReservationPayment::whereIn('property_id', $properties)
            ->where('stato', 'pagato')
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->get();

        return view('dashboard-host', [
            'today' => $today,
            'totalePrenotazioniMese' => $prenotazioniMese->count(),
            'fatturatoMese' => $pagamentiMese->sum('importo'),
            'commissioniMese' => $pagamentiMese->sum('commissione_dukares'),
            'pagamentiInAttesa' => ReservationPayment::whereIn('property_id', $properties)
                ->where('stato', 'in_attesa')
                ->count(),

            'arriviOggi' => $arriviOggi,
            'partenzeOggi' => $partenzeOggi,
            'soggiorniInCorso' => $soggiorniInCorso,

            'ultimePrenotazioni' => Reservation::whereIn('property_id', $properties)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
        ]);
    }
}


