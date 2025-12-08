<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Lista dei metodi di pagamento disponibili
     */
    public function index()
    {
        $methods = PaymentMethod::orderBy('id', 'asc')->get();
        return view('payments.methods.index', compact('methods'));
    }

    /**
     * Mostra form per creare un nuovo metodo
     */
    public function create()
    {
        return view('payments.methods.create');
    }

    /**
     * Salva un nuovo metodo di pagamento
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|string|max:50',
        ]);

        PaymentMethod::create([
            'nome' => $request->nome,
            'tipo' => $request->tipo,
            'enabled' => $request->enabled ?? true,
        ]);

        return redirect()->route('payment-methods.index')
                         ->with('success', 'Metodo di pagamento aggiunto con successo.');
    }

    /**
     * Modifica metodo di pagamento
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('payments.methods.edit', compact('paymentMethod'));
    }

    /**
     * Salvataggio modifica
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|string|max:50',
        ]);

        $paymentMethod->update([
            'nome' => $request->nome,
            'tipo' => $request->tipo,
            'enabled' => $request->enabled ?? true,
        ]);

        return redirect()->route('payment-methods.index')
                         ->with('success', 'Metodo aggiornato con successo.');
    }

    /**
     * Cancella un metodo
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return redirect()->route('payment-methods.index')
                         ->with('success', 'Metodo eliminato.');
    }
}
