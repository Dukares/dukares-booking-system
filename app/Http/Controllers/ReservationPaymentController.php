<?php

namespace App\Http\Controllers;

use App\Models\ReservationPayment;
use App\Models\Reservation;
use App\Models\Property;
use App\Models\PaymentMethod;
use App\Models\DukaresOwnerSetting;
use Illuminate\Http\Request;

class ReservationPaymentController extends Controller
{
    /**
     * Lista di tutti i pagamenti (solo admin)
     */
    public function index()
    {
        $pagamenti = ReservationPayment::with(['reservation', 'property', 'method'])
                        ->orderBy('id', 'desc')
                        ->paginate(20);

        return view('payments.reservations.index', compact('pagamenti'));
    }

    /**
     * Crea manualmente un pagamento (per test o prenotazioni offline)
     */
    public function create()
    {
        $reservations = Reservation::all();
        $properties = Property::all();
        $methods = PaymentMethod::all();

        return view('payments.reservations.create', compact('reservations', 'properties', 'methods'));
    }

    /**
     * Salvataggio pagamento manuale
     */
    public function store(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'property_id' => 'required|exists:properties,id',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'importo' => 'required|numeric|min:0',
        ]);

        // Calcolo commissioni
        $settings = DukaresOwnerSetting::first();
        $commissione = ($request->importo * $settings->commissione_percentuale) / 100;
        $importoStruttura = $request->importo - $commissione;

        ReservationPayment::create([
            'reservation_id' => $request->reservation_id,
            'property_id' => $request->property_id,
            'payment_method_id' => $request->payment_method_id,
            'importo' => $request->importo,
            'commissione_dukares' => $commissione,
            'importo_struttura' => $importoStruttura,
            'stato' => 'pagato',
        ]);

        return redirect()->route('payments.reservations.index')
                         ->with('success', 'Pagamento registrato con successo.');
    }

    /**
     * Mostra un pagamento specifico
     */
    public function show($id)
    {
        $p = ReservationPayment::with(['reservation', 'property', 'method'])
                                ->findOrFail($id);

        return view('payments.reservations.show', compact('p'));
    }

    /**
     * Segna un pagamento come rimborsato
     */
    public function refund($id)
    {
        $payment = ReservationPayment::findOrFail($id);
        $payment->update(['stato' => 'rimborsato']);

        return back()->with('success', 'Pagamento rimborsato con successo.');
    }

    /**
     * Segna un pagamento come fallito
     */
    public function fail($id)
    {
        $payment = ReservationPayment::findOrFail($id);
        $payment->update(['stato' => 'fallito']);

        return back()->with('success', 'Pagamento segnato come fallito.');
    }

    /**
     * Elimina un pagamento
     */
    public function destroy($id)
    {
        $payment = ReservationPayment::findOrFail($id);
        $payment->delete();

        return back()->with('success', 'Pagamento eliminato.');
    }
}
