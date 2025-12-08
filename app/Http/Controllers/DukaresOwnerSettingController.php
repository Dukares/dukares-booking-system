<?php

namespace App\Http\Controllers;

use App\Models\DukaresOwnerSetting;
use Illuminate\Http\Request;

class DukaresOwnerSettingController extends Controller
{
    /**
     * Mostra la pagina impostazioni bancarie del proprietario del portale
     */
    public function edit()
    {
        // Recupera la riga (solo 1), oppure la crea
        $settings = DukaresOwnerSetting::firstOrCreate(['id' => 1]);

        return view('admin.owner-settings.edit', compact('settings'));
    }

    /**
     * Aggiorna le impostazioni del proprietario del portale
     */
    public function update(Request $request)
    {
        $request->validate([
            'iban' => 'nullable|string|max:50',
            'intestatario_conto' => 'nullable|string|max:255',
            'swift' => 'nullable|string|max:20',
            'paypal_email' => 'nullable|email|max:255',
            'revolut' => 'nullable|string|max:255',
            'wise' => 'nullable|string|max:255',
            'commissione_percentuale' => 'required|numeric|min:0|max:100',
            'commissione_minima' => 'nullable|numeric|min:0',
            'commissione_massima' => 'nullable|numeric|min:0',
            'ciclo_fatturazione' => 'required|in:settimanale,quindicinale, mensile',
        ]);

        $settings = DukaresOwnerSetting::firstOrCreate(['id' => 1]);

        $settings->update([
            'iban' => $request->iban,
            'intestatario_conto' => $request->intestatario_conto,
            'swift' => $request->swift,
            'paypal_email' => $request->paypal_email,
            'revolut' => $request->revolut,
            'wise' => $request->wise,
            'commissione_percentuale' => $request->commissione_percentuale,
            'commissione_minima' => $request->commissione_minima,
            'commissione_massima' => $request->commissione_massima,
            'ciclo_fatturazione' => $request->ciclo_fatturazione,
        ]);

        return redirect()
            ->route('admin.owner.settings.edit')
            ->with('success', 'Impostazioni aggiornate con successo.');
    }
}
