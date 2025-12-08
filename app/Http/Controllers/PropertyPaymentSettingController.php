<?php

namespace App\Http\Controllers;

use App\Models\PropertyPaymentSetting;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyPaymentSettingController extends Controller
{
    /**
     * Mostra la pagina impostazioni pagamento per la struttura.
     */
    public function edit($propertyId)
    {
        $property = Property::findOrFail($propertyId);

        // Recupera impostazioni oppure le crea se non esistono
        $settings = PropertyPaymentSetting::firstOrCreate(
            ['property_id' => $property->id]
        );

        return view('payments.property.edit', compact('property', 'settings'));
    }

    /**
     * Salva/aggiorna impostazioni pagamento della struttura
     */
    public function update(Request $request, $propertyId)
    {
        $property = Property::findOrFail($propertyId);

        $request->validate([
            'accetta_contanti' => 'nullable|boolean',
            'accetta_pos' => 'nullable|boolean',
            'accetta_bonifico' => 'nullable|boolean',
            'online_enabled' => 'nullable|boolean',
            'gateway' => 'nullable|string|max:50',
            'api_key_public' => 'nullable|string|max:255',
            'api_key_secret' => 'nullable|string|max:255',
            'anticipo_percentuale' => 'nullable|integer|min:0|max:100',
        ]);

        PropertyPaymentSetting::updateOrCreate(
            ['property_id' => $property->id],
            [
                'accetta_contanti' => $request->accetta_contanti ?? 0,
                'accetta_pos' => $request->accetta_pos ?? 0,
                'accetta_bonifico' => $request->accetta_bonifico ?? 0,
                'online_enabled' => $request->online_enabled ?? 0,
                'gateway' => $request->gateway,
                'api_key_public' => $request->api_key_public,
                'api_key_secret' => $request->api_key_secret,
                'anticipo_percentuale' => $request->anticipo_percentuale,
            ]
        );

        return redirect()
            ->route('property.payments.edit', $property->id)
            ->with('success', 'Impostazioni pagamento aggiornate con successo.');
    }
}
