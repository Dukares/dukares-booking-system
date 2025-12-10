<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ICSImportService;

class ICSController extends Controller
{
    /**
     * Mostra il form di test per l'import ICS
     */
    public function testForm()
    {
        return view('ics.test');
    }

    /**
     * Esegue l'import ICS
     */
    public function runTest(Request $request)
    {
        // Validazione dei campi
        $request->validate([
            'property_id' => 'required|integer',
            'ics_file'    => 'required|file|mimes:ics',
        ]);

        // Legge il contenuto del file ICS caricato
        $content = file_get_contents(
            $request->file('ics_file')->getRealPath()
        );

        // Usa il servizio di import
        $service = new ICSImportService();
        $result  = $service->importICS(
            $request->property_id,
            $content
        );

        // Il servizio restituisce un ARRAY con i dettagli
        $imported   = $result['imported'] ?? 0;
        $duplicates = $result['duplicates'] ?? 0;
        $total      = $result['total'] ?? ($imported + $duplicates);

        $message = "Importazione completata! Prenotazioni nel file: $total. "
                 . "Inserite: $imported. Duplicate ignorate: $duplicates.";

        return back()->with('success', $message);
    }
}


